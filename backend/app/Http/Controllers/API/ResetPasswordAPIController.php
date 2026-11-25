<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use \Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class ResetPasswordAPIController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        }
        );

        /*return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($response)
            : $this->sendResetFailedResponse($request, $response);*/
        if ($response == Password::PASSWORD_RESET) {
            Log::debug('La constraseña se cambio con éxito', ['email'=>$request->get('email')]);
            return response()->json(['success' => true, 'message' => 'La contraseña se cambio con éxito. Ahora puedes ingresar.']);
        } else {
            Log::debug('Ocurrio un error al intentar cambiar la contraseña', [
                'email'=>$request->get('email'),
                'response'=>$response,
            ]);
            return response()->json(['success' => false, 'error' => ['message' => $response]], 401);
        }
    }
}
