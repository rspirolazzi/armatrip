<?php

namespace Rspirolazzi\Coupons\Exceptions;

use Exception;

class OutOfTimeException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'Promotion code is out of time.';

    /**
     * @var int
     */
    protected $code = 404;
}
