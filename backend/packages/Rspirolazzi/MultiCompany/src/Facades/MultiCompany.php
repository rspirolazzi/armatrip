<?php

namespace Rspirolazzi\MultiCompany\Facades;

use Illuminate\Support\Facades\Facade;

class MultiCompany extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'multicompany';
    }
}
