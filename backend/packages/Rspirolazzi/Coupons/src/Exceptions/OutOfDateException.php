<?php

namespace Rspirolazzi\Coupons\Exceptions;

use Exception;

class OutOfDateException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'Promotion code is out of date.';

    /**
     * @var int
     */
    protected $code = 404;
}
