<?php

namespace Rspirolazzi\Coupons\Exceptions;

use Exception;

class InvalidCouponException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'Invalid promotion code was passed.';

    /**
     * @var int
     */
    protected $code = 404;
}
