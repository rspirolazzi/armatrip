<?php

namespace Rspirolazzi\Coupons\Exceptions;

use Exception;

class AlreadyUsedException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'Promotion code is already used by current user.';

    /**
     * @var int
     */
    protected $code = 403;
}
