<?php

namespace App\Http\Controllers\API;

use App\Exceptions\OrderValidationException;
use App\Helpers\Errors;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\RegisterUserAPIRequest;
use App\Models\Admin\SettingManager;
use App\Models\Store;
use App\Models\Token;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use App\User;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use JWTAuth;
use JWTAuthException;
use JWTFactory;
use Mail;
use Sms;
use Validator;
use Rspirolazzi\Coupons\Models\Coupon;

class ApiController extends AppBaseController
{
    protected $orderRepository;
    protected $userRepository;

    /**
     * ApiController constructor.
     * @param OrderRepository $orderRepo
     * @param UserRepository $userRepo
     */
    public function __construct(OrderRepository $orderRepo, UserRepository $userRepo)
    {
        $this->orderRepository = $orderRepo;
        $this->userRepository = $userRepo;
    }

    /**
     * API Register
     *
     * @param RegisterUserAPIRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterUserAPIRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = null;
            $json = null;
            $message = '';
            $response = [];
            if ($request->token) {
                $user = JWTAuth::toUser($request->token);
                $message = 'Tu usuario se registro con éxito';
            } else {
                $user = $this->userRepository->register($request);
                $verification_type = SettingManager::get("register_validation", $request->get('verification_type', null));
                if ($verification_type) {
                    $json = $this->userRepository->verification($user, $request);
                    if (!$json) {
                        throw new OrderValidationException('Ocurrio un error con la validación de usuario. Por favor vuelva a intentarlo');
                    }
                    switch ($verification_type) {
                        case UserRepository::$VERIFICATION_TYPE_FACEBOOK:
                            $message = 'Facebook no habilitado';
                            break;
                        case UserRepository::$VERIFICATION_TYPE_SMS:
                            $message = 'Tu registro se completo con éxito. En breve recibiras un SMS para activarla';
                            break;
                        case UserRepository::$VERIFICATION_TYPE_CAPTCHA:
                            $message = 'CAPTCHA no habilitado';
                            break;
                        case UserRepository::$VERIFICATION_TYPE_EMAIL:
                            $message = 'EMAIL no habilitado';
                            break;
                    }
                } else {
                    $message = 'El registro se completo con éxito';
                    $response['jwt'] = JWTAuth::fromUser($user);
                    $response['user'] = $user->toArray();
                }
            }
            $order = $this->orderRepository->findOrCreate($user, $request);
            $response = array_merge($order->toArray(), $response);
            DB::commit();
            return $this->sendResponse($response, $message);
        } catch (OrderValidationException $e) {
            DB::rollback();
            Log::error('Ocurrio un error al intentar registrar al usuario', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'traceasstring' => $e->getTraceAsString(),
            ]);
            return $this->sendError($e->getMessage());
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Ocurrio un error al intentar registrar al usuario', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'traceasstring' => $e->getTraceAsString(),
            ]);
            return $this->sendError('Ocurrio un error inesperado');
        }
    }

    /**
     * API Register
     *
     * @param RegisterUserAPIRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function continueWithUser(RegisterUserAPIRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = JWTAuth::toUser($request->token);
            $order = $this->orderRepository->findOrCreate($user, $request);
            DB::commit();
            return $this->sendResponse($user, 'Tu registro se completo con éxito. En breve recibiras un SMS para activarla');
        } catch (OrderValidationException $e) {
            DB::rollback();
            Log::error('Ocurrio un error al intentar continuar con el usuario', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'traceasstring' => $e->getTraceAsString(),
            ]);
            return $this->sendError($e->getMessage());
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Ocurrio un error al intentar continuar con el usuario', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'traceasstring' => $e->getTraceAsString(),
            ]);
            return $this->sendError('Ocurrio un error inesperado');
        }
    }

    /**
     * API Verify User
     *
     * @param $verification_code
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyUser($verification_code)
    {
        $check = Token::where('token', $verification_code)->where('source', Token::$_SOURCE_EMAIL)->first();
        if (!is_null($check)) {
            $user = User::find($check->user_id);
            if ($user->is_verified == 1) {
                return response()->json([
                    'success' => true,
                    'message' => 'La cuenta ya esta verificada.'
                ]);
            }
            $user->verified_source = Token::$_SOURCE_EMAIL;
            $user->verified_time = Carbon::now();
            $user->is_verified = 1;
            $user->save();
            $check->delete();
            Log::info('La cuenta se verifico con éxito', ['user_id' => $user->id]);
            return redirect(env('APP_WEB_URL') . '/#?message=Tu cuenta se verifico con éxito.&type=success&code=' . $verification_code);
        }
        Log::error('El código de verificacion no existe o ya fue verificado');
        return redirect(env('APP_WEB_URL') . '/#?message=Ocurrio un error al intenar verificar tu cuenta.&type=error');
        return $this->sendError("Verification code is invalid.", Errors::E_VERIFICATION_USER);
    }

    /**
     * API Verify User
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyUserBySMS(Request $request)
    {
        $verification_code = trim($request->input('verification_code'));
        $phone_code = trim($request->input('phone_code'));
        $phone_number = trim($request->input('phone_number'));

        $limiter = app(RateLimiter::class);
        $key = 'sms-verify:' . sha1("{$phone_code}-{$phone_number}");
        $tokens = Token::where('source', Token::$_SOURCE_SMS)
            ->whereHas('user', function ($query) use ($phone_code, $phone_number) {
                $query->where('phone_code', $phone_code)->where('phone_number', $phone_number);
            });

        $check = (clone $tokens)->where('token', $verification_code)
            ->where('created_at', '>=', Carbon::now()->subMinutes(config('sms.code.expires_minutes')))
            ->first();

        if (!$check) {
            $limiter->hit($key, config('sms.code.window_minutes'));
            if ($limiter->tooManyAttempts($key, config('sms.code.max_attempts'))) {
                // Hay que pedir un código nuevo.
                $tokens->delete();
                $limiter->clear($key);
            }
            Log::error('El código de verificacion no es válido');
            return $this->sendError('El código no es válido o venció. Revisalo o pedí uno nuevo.');
        }

        $limiter->clear($key);
        $user = $check->user;
        $user->verified_source = Token::$_SOURCE_SMS;
        $user->verified_time = Carbon::now();
        $user->is_verified = 1;
        $user->save();
        $check->delete();
        Log::info('La cuenta se verifico con éxito', ['user_id' => $user->id]);
        $token = JWTAuth::fromUser($user);
        return $this->sendResponse(['token' => $token, 'user' => $user->toArray()], "Verificaste con éxito tu celular.");
    }

    public function getAuthUser(Request $request)
    {
        try {
            $user = JWTAuth::toUser($request->token);
            return $this->sendResponse($user);
        } catch (\Exception $e) {
            Log::error('Ocurrio un error inesperado al intentar obtener el usuario', [$e->getTraceAsString()]);
            return $this->sendError($e->getMessage());

        }
    }

    /**
     * API Recover Password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reSendSMS(Request $request)
    {
        $value = $request->get('value');
        $ruleMobile = ['value' => ['required', 'regex:/^(?:(?:00)?549?)?0?(?:11|[2368]\d)(?:(?=\d{0,2}15)\d{2})??\d{8}$/']];
        $user = null;
        $error_message = "";

        //Se valida que el valor sea un telefono
        $validator = Validator::make($request, $ruleMobile);
        if (!$validator->fails()) {
            $user = $this->getUserToResend('telephone', $value);
            if (!$user) {
                $error_message = "El celular ingresado no se encuentra registrado.";
                return $this->sendError(['message' => $error_message]);
            } else if ($user->is_verified) {
                $this->sendResetPasswordToEmail($value);
                return $this->sendResponse('Se envió un éxito un email para resetear la contraseña.');
            } else {
                return response()->json($this->verifyUserBySMS($user));
            }
        }
        return $this->sendError('El celular ingresado no es valido');
    }

    protected function getUserToResend(string $field, $value)
    {
        return User::where($field, $value)->first();
    }

    protected function sendResetPasswordToEmail($value)
    {
        try {
            Password::sendResetLink($value, function (Message $message) {
                $message->subject('Cambio de contraseña');
            });
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return $this->sendError($error_message);
        }
    }

    public function config(Request $request)
    {
        $config = [];
        try {
            $config['coupon'] = [
                'isVisible' => Coupon::available()->count() > 0,
                'length' => config('coupons.code_length', 8),
            ];
            $config['payment'] = [
                'isEnabled' => (bool)SettingManager::get('mp_enabled', env('MP_ENABLED', false)),
                'isTest' => (bool)SettingManager::get('mp_enabled_test', env('MP_ENABLED_TEST', false)),
            ];
            $turismo_individual = SettingManager::get('store_code_individual', 'turismo_individual');
            $turismo_individual_url = SettingManager::get('store_code_individual', '/turismo-individual');
            $store = Store::where('code', $turismo_individual)->first();
            if ($store) {
                $config['individual_tourism'] = $store->toArray();
                $config['individual_tourism']['url'] = $turismo_individual_url;

            }
        } catch (\Exception $ex) {
            Log::error('Ocurrio un error al devolver las configuraciones');
        }
        return response()->json(['success' => true, 'data' => $config]);
    }

    public function log(Request $request)
    {
        try {
            $message = $request->get('message');
            $data = json_encode($request->get('data'));
            switch ($request->get('type')) {
                case 'error':
                    Log::error('[WEB]' . $message, ['data' => $data]);
                    break;
                case 'debug':
                    Log::debug('[WEB]' . $message, ['data' => $data]);
                    break;
                default:
                    Log::info('[WEB]' . $message, ['data' => $data]);
                    break;
            }

        } catch (\Exception $ex) {
            Log::error('Ocurrio un error al devolver las configuraciones');
        }
        return $this->sendResponse([]);
    }
}
