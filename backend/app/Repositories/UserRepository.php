<?php

namespace App\Repositories;

use App\Exceptions\OrderValidationException;
use App\Models\Admin\SettingManager;
use App\Models\Token;
use App\User;
use Hash;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Log;
use InfyOm\Generator\Common\BaseRepository;
use Rspirolazzi\Sms\Exceptions\SMSException;
use Sms;
use Illuminate\Support\Str;


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
    /**
     * Crea el usuario si el email es nuevo. Un usuario existente no se modifica:
     * todavía no se probó que quien se registra sea su dueño.
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\User
     * @throws OrderValidationException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function register(\Illuminate\Http\Request $request)
    {
        $credentials = $request->only('name', 'email', 'phone_code', 'phone_number');

        $name = trim($credentials['name']);
        $email = trim($credentials['email']);
        $phone_code = trim($credentials['phone_code']);
        $phone_number = trim($credentials['phone_number']);

        $user = $this->findByField('email', $email)->first();
        if (!$user) {
            $user = $this->create([
                    'name' => $name,
                    'email' => $email,
                    'phone_code' => $phone_code,
                    'phone_number' => $phone_number,
                    'password' => Hash::make(Str::random(32)),
                    'is_verified' => 0,
                ]
            );
            Log::info('El usuario se genero con éxito', ['user_id' => $user->id]);
        } elseif ((string) $user->phone_code !== $phone_code || (string) $user->phone_number !== $phone_number) {
            // No se revela el celular guardado.
            throw new OrderValidationException('Este email ya está registrado con otro celular. Ingresá el celular con el que te registraste.');
        }
        return $user;
    }

    /**
     * Tipo de validación del registro. Lo decide solo el servidor; hoy solo existe SMS.
     *
     * @return string
     */
    public static function verificationType()
    {
        return SettingManager::get('register_validation') ?: self::$VERIFICATION_TYPE_SMS;
    }

    /**
     * Envía la validación del registro. La sesión se entrega recién al verificar el código.
     *
     * @param User $user
     * @return bool
     * @throws OrderValidationException
     */
    public function verification(User $user)
    {
        $verification_type = self::verificationType();
        $enabled = SettingManager::get('register_validation_' . self::$VERIFICATION_TYPE_SMS . '_enabled', true);
        if ($verification_type !== self::$VERIFICATION_TYPE_SMS || !$enabled) {
            Log::error('La validación de registro configurada no está disponible', ['type' => $verification_type]);
            throw new OrderValidationException('La validación de registro no está disponible. Por favor, contactanos.');
        }
        return $this->verificationSms($user);
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
