<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\ApplyCouponAPIRequest;
use App\Http\Requests\API\CreateCouponAPIRequest;
use App\Http\Requests\API\UpdateCouponAPIRequest;
use App\Models\Coupon;
use App\Repositories\API\CouponRepository;
use Coupons;
use Illuminate\Support\Facades\Log;
use Response;
use JWTAuth;
use Rspirolazzi\Coupons\Exceptions\InvalidCouponException;
use Rspirolazzi\Coupons\Exceptions\OutOfDateException;
use Rspirolazzi\Coupons\Exceptions\OutOfTimeException;
use Rspirolazzi\Coupons\Exceptions\UnauthenticatedException;
use App\Helpers\Errors;
use DB;
use Exception;

/**
 * Class CouponController
 * @package App\Http\Controllers\API
 */
class CouponAPIController extends AppBaseController
{
    /** @var  CouponRepository */
    private $couponRepository;

    public function __construct(CouponRepository $couponRepo)
    {
        $this->couponRepository = $couponRepo;
    }
    protected function getUser()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $user;
    }

    /**
     * Verifica que el cupón exista y sea valido
     * POST|HEAD /code
     * POST|HEAD /token
     *
     * @param ApplyCouponAPIRequest $request
     * @return Response
     */
    public function check(ApplyCouponAPIRequest $request)
    {
        //$this->validate($request, ['code' => 'required']);
        //TODO chequear que valide el code
        $code = $request->get('code');
        $user = $this->getUser();

        try{
            $coupon = Coupons::check($code);
            if(!$coupon){
                Log::error('El cupón solicitado expiro', ['code'=>$code, 'user_id'=>$user->id]);
                return $this->sendError('El cupón no esta vigente');
            }
        }catch(OutOfTimeException $e){
            Log::error('El cupón no corre', ['code'=>$code, 'user_id'=>$user->id]);
            $coupon = Coupons::byCode($code);
            return $this->sendError( "Este cupón solo puede ser usado de {$coupon->hs_start_at_short} a {$coupon->hs_end_at_short}");
        }catch(OutOfDateException $e){
            Log::error('El cupón ingresado no esta fuera del rango de fechas', ['code'=>$code, 'user_id'=>$user->id]);
            return $this->sendError('El cupón ingresado no existe');
        }catch(InvalidCouponException $e){
            Log::error('Ocurrio un error. El cupón no existe', ['code'=>$code, 'user_id'=>$user->id]);
            return $this->sendError('El cupón ingresado no existe');
        }

        return $this->sendResponse(['code'=>$code, 'reward'=>$coupon->reward], 'El cupón es valido');
    }
    /**
     * Verifica que el cupón exista y sea valido
     * POST|HEAD /code
     * POST|HEAD /token
     *
     * @param ApplyCouponAPIRequest $request
     * @return Response
     */
    public function apply(ApplyCouponAPIRequest $request)
    {
        //$this->validate($request, ['code' => 'required']);
        //TODO chequear que valide el code
        //TODO chequear que valide el store_id
        $code = $request->get('code');
        $user = $this->getUser();

        try{
            $coupon = Coupons::check($code);
            if($coupon){
                $coupon_find = DB::table(config('coupons.relation_table'))
                    ->where('order_id', $user->active_order->id)
                    ->where('user_id', $user->id)
                    ->first();
                if(!$coupon_find){
                    $coupon = Coupons::apply($code, $user->active_order->id);
                }
            }else{
                Log::error('El cupón solicitado expiro', ['code'=>$code, 'user_id'=>$user->id]);
                return $this->sendError('El cupón no esta vigente');
            }
        }catch(UnauthenticatedException $e){
            Log::error('Ocurrio un error inesperado. No existe el usuario', ['code'=>$code, 'user'=>$user->toArray()]);
            return $this->sendError('Ocurrio un error inesperado. El usuario no es valido', Errors::$E_COUPON_Unauthenticated);
        }catch(InvalidCouponException $e){
            Log::error('Ocurrio un error inesperado. No existe el usuario', ['code'=>$code, 'user'=>$user->toArray()]);
            return $this->sendError('El cupón ingresado no existe ', Errors::$E_COUPON_InvalidCoupon);
        }catch(OutOfDateException $e){
            Log::error('El cupón esta OutOfDateException', ['code'=>$code, 'user_id'=>$user->id]);
            return $this->sendError('El cupón ingresado esta vencido', Errors::$E_COUPON_Out_Of_Date);
        }catch(OutOfTimeException $e){
            Log::error('El cupón esta OutOfTimeException', ['code'=>$code, 'user_id'=>$user->id]);
            return $this->sendError('El cupón ingresado esta vencido', Errors::$E_COUPON_Out_Of_Time);
        }

        return $this->sendResponse(['code'=>$code], 'El cupón se aplico con éxito');
    }
    /**
     * Verifica que el cupón exista y sea valido
     * POST|HEAD /code
     * POST|HEAD /token
     *
     * @param ApplyCouponAPIRequest $request
     * @return Response
     */
    public function remove(ApplyCouponAPIRequest $request)
    {
        //$this->validate($request, ['code' => 'required']);
        //TODO chequear que valide el code
        //TODO chequear que valide el store_id
        $code = $request->get('code');
        $user = $this->getUser();

        try{
            $coupon_find = DB::table(config('coupons.relation_table'))
                ->where('order_id', $user->active_order->id)
                ->where('user_id', $user->id)
                ->delete();
            if(!$coupon_find){
                Log::error('El cupón solicitado no exíste para el usuario', ['code'=>$code, 'user_id'=>$user->id]);
            }else{
                Log::debug('El cupón solicitado se borró', ['code'=>$code, 'user_id'=>$user->id]);
            }
        }catch(Exception $e){
            Log::error('Ocurrio un error inesperado al intentar borrar el cupón', ['code'=>$code, 'user'=>$user->toArray()]);
            return $this->sendError('Ocurrio un error inesperado al intentar sacar el cupón');
        }catch(UnauthenticatedException $e){
            Log::error('Ocurrio un error inesperado. No existe el usuario', ['code'=>$code, 'user'=>$user->toArray()]);
            return $this->sendError('Ocurrio un error inesperado. El usuario no es valido', Errors::$E_COUPON_Unauthenticated);
        }catch(InvalidCouponException $e){
            Log::error('Ocurrio un error inesperado. No existe el usuario', ['code'=>$code, 'user'=>$user->toArray()]);
            return $this->sendError('El cupón ingresado no existe ', Errors::$E_COUPON_InvalidCoupon);
        }catch(OutOfDateException $e){
            Log::error('El cupón esta OutOfDateException', ['code'=>$code, 'user_id'=>$user->id]);
            return $this->sendError('El cupón ingresado esta vencido', Errors::$E_COUPON_Out_Of_Date);
        }catch(OutOfTimeException $e){
            Log::error('El cupón esta OutOfTimeException', ['code'=>$code, 'user_id'=>$user->id]);
            return $this->sendError('El cupón ingresado esta vencido', Errors::$E_COUPON_Out_Of_Time);
        }

        return $this->sendResponse(['code'=>$code], 'El cupón se sacó con éxito');
    }
}
