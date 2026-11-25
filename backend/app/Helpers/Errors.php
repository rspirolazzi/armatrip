<?php

namespace App\Helpers;

use App\Models\Item;
use Storage;

/**
 * Created by PhpStorm.
 * User: rodrigos
 * Date: 11/2/2018
 * Time: 10:42 PM
 */
class Errors
{
    /* Sin Errores*/
    public static $E_0 = 0;
    /* Usuario o contraseña invalido*/
    public static $E_LOGIN_REJECTED = 1000;
    /* Token invalido */
    public static $E_INVALID_TOKEN = 1001;
    /*Error al verificar el usuario*/
    public static $E_VERIFICATION_USER = 1002;
    /* Sin Errores*/
    public static $E_FORM_VALIDATE = 2000;
    /* El paquete no existe */
    public static $E_PACKAGE_NOT_FOUND= 3000;
    /* La cantidad seleccionada de productos no coincide con los productos en el paquete */
    public static $E_PACKAGE_WRONG_COUNT_PRODUCTS= 3001;
    /* Error de cupon UnauthenticatedException */
    public static $E_COUPON_Unauthenticated = 4001;
    /* Error de cupon OutOfDateException */
    public static $E_COUPON_Out_Of_Date = 4002;
    /* Error de cupon OutOfTimeException */
    public static $E_COUPON_Out_Of_Time = 4003;
    /* Error de cupon InvalidCouponException */
    public static $E_COUPON_InvalidCoupon = 4004;
    
    /* Error de cupon MP */
    public static $E_MP_DISABLED = 5000;
    public static $E_MP_HANDLER = 5001;
    public static $E_MP_ORDER_NOT_PAYMENT = 5002;
    public static $E_MP_UNKNOWN = 5999;


    /* Errores para DESCONOCIDO*/
    public static $E_UNKNOWN = 9999;
}