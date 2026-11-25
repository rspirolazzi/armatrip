<?php
/**
 * Created by PhpStorm.
 * User: rodrigos
 * Date: 2019-01-07
 * Time: 5:50 p.m.
 */

namespace Rspirolazzi\MultiCompany\Traits;

namespace Illuminate\Database\Eloquent;

use Rspirolazzi\MultiCompany\Scopes\MultiCompany as MultiCompanyScope;

trait MultiCompany
{

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new MultiCompanyScope);
    }
}