<?php

namespace App\Repositories;

use App\Exceptions\OrderValidationException;
use App\Models\Admin\SettingManager;
use App\Models\Token;
use App\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use InfyOm\Generator\Common\BaseRepository;
use Rspirolazzi\Sms\Exceptions\SMSException;
use Sms;
use Illuminate\Support\Str;
use Tymon\JWTAuth\JWTAuth;
use Validator;


/**
 * Class OrderRepository
 * @package App\Repositories
 * @version May 20, 2018, 2:07 pm UTC
 *
 * @method User findWithoutFail($id, $columns = ['*'])
 * @method User find($id, $columns = ['*'])
 * @method User first($columns = ['*'])
 */
class UserRepository extends BaseRepository
{
    public static $VERIFICATION_TYPE_SMS = 'sms';
    public static $VERIFICATION_TYPE_EMAIL = 'email';
    public static $VERIFICATION_TYPE_CAPTCHA = 'captcha';
    public static $VERIFICATION_TYPE_FACEBOOK = 'facebook';
    /**
     * @var array
     */
    protected $fieldSearchable = [
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return User::class;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \App\User
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function register(\Illuminate\Http\Request $request)
    {
        $credentials = $request->only('name', 'email', 'phone_code', 'phone_number', 'isVerified');

        $name = trim($credentials['name']);
        $email = trim($credentials['email']);
        $phone_code = trim($credentials['phone_code']);
        $phone_number = trim($credentials['phone_number']);
        $password = Hash::make(Str::random(8));
        $is_verified = 0;

        $user = $this->findByField('email', $email)->first();
        if (!$user) {
            $user = $this->create([
                    'name' => $name,
                    'email' => $email,
                    'phone_code' => $phone_code,
                    'phone_number' => $phone_number,
                    'password' => Hash::make($password),
                    'is_verified' => $is_verified,
                ]
            );
            Log::info('El usuario se genero con éxito', $user->toArray());
        } else {
            $user->name = $name;
            $user->email = $email;
            $user->phone_code = $phone_code;
            $user->phone_number = $phone_number;
            $user->save();
            Log::info('El usuario se actualizo con éxito', $user->toArray());
        }
        return $user;
    }

    /**
     * @param User $user
     * @param \Illuminate\Http\Request $request
     * @return array|mixed
     */
    public function verification(User $user, \Illuminate\Http\Request $request)
    {
        $verification_type = SettingManager::get("register_validation", $request->get('verification_type', null));
        if (!$verification_type) {
            Log::debug('No se configuro ningun tipo de validacion de registro');
            return true;
        }
        Log::debug('Validacion de registro por ' . $verification_type);
        $json = false;
        switch ($verification_type) {
            case self::$VERIFICATION_TYPE_FACEBOOK:
                if (SettingManager::get("register_validation_" . self::$VERIFICATION_TYPE_FACEBOOK . "_enabled", false)) {
                    $json = $this->verificationFacebook($user);
                }
                break;
            case self::$VERIFICATION_TYPE_SMS:
                if (SettingManager::get("register_validation_" . self::$VERIFICATION_TYPE_SMS . "_enabled", true)) {
                    $json = $this->verificationSms($user);
                }
                break;
            case self::$VERIFICATION_TYPE_CAPTCHA:
                if (SettingManager::get("register_validation_" . self::$VERIFICATION_TYPE_CAPTCHA . "_enabled", false)) {
                    $json = $this->VerificationCaptcha($user, $request);
                }
                break;
            case self::$VERIFICATION_TYPE_EMAIL:
            default:
                if (SettingManager::get("register_validation_" . self::$VERIFICATION_TYPE_EMAIL . "_enabled", false)) {
                    $json = $this->VerificationEmail($user);
                }
                break;

        }
        return $json;

    }

    /**
     * @param $user
     * @return array
     */
    protected function verificationEmail($user)
    {
        Log::debug('Verificación con Email');
        $verification_code = Token::generateCodeEmail();
        Token::where('user_id', $user->id)->delete();
        Token::create([
            'user_id' => $user->id,
            'token' => $verification_code,
            'source' => Token::$_SOURCE_EMAIL,
        ]);
        Log::debug('Token creado');
        $subject = "Por favor confirma tu casilla.";

        Mail::send('email.verify', ['name' => $user->name, 'verification_code' => $verification_code], function ($mail) use ($user, $subject) {
            $mail->from(getenv('MAIL_FROM_ADDRESS'), "Armatrip");
            $mail->to($user->email, $user->name);
            $mail->subject($subject);
        });
        return ['success' => true, 'message' => 'Thanks for signing up! Please check your email to complete your registration.'];
    }

    /**
     * @param $user
     * @return array
     */
    protected function verificationCaptcha($user, \Illuminate\Http\Request $request)
    {
        Log::debug('Verificación con Email');
        $verification_code = '';
        Log::debug('Token creado');
        $subject = "Por favor confirma tu casilla.";
        return ['success' => true, 'message' => 'Thanks for signing up! Please check your email to complete your registration.'];
    }

    /**
     * @param $user
     * @return array
     */
    protected function verificationFacebook($user)
    {
        Log::debug('Verificación con Facebook');
        $token = JWTAuth::fromUser($user);
        $user->verified_source = Token::$_SOURCE_EMAIL;
        $user->verified_time = Carbon::now();
        $user->is_verified = 1;
        $user->save();
        return ['success' => true, 'data' => ['token' => $token, 'user' => $user->toArray()]];
    }

    /**
     * Evita pedir códigos sin límite para un mismo celular.
     *
     * @throws OrderValidationException
     */
    private function limitSmsRequests(User $user)
    {
        $limiter = app(RateLimiter::class);
        $key = 'sms-request:' . sha1("{$user->phone_code}-{$user->phone_number}");
        if ($limiter->tooManyAttempts($key, config('sms.code.max_requests'))) {
            throw new OrderValidationException('Pediste demasiados códigos. Probá de nuevo en unos minutos.');
        }
        $limiter->hit($key, config('sms.code.window_minutes'));
    }

    /**
     * @param $user
     * @return mixed
     */
    protected function verificationSms($user)
    {
        try {
            Log::debug('Verificación con SMS');
            $this->limitSmsRequests($user);
            $verification_code = Token::generateCodeSMS();
            Token::where('user_id', $user->id)->delete();
            Token::create([
                'user_id' => $user->id,
                'token' => $verification_code,
                'source' => Token::$_SOURCE_SMS,
            ]);
            Log::debug('Token creado');
            $telephone = "{$user->phone_code}{$user->phone_number}";
            if (Sms::createMessage($telephone, $verification_code)) {
                Log::info('El sms se envio con éxito', ['phone' => $telephone]);
                return true;
            } else {
                return false;
            }
        } catch (SMSException $ex) {
            Log::error('Ocurrio un error al enviar un sms de activación', [
                'message' => $ex->getMessage(),
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ]);
            return false;
        }
    }
}
