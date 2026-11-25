<?php
/**
 * Created by PhpStorm.
 * User: rodrigos
 * Date: 2019-02-04
 * Time: 10:03 a.m.
 */

namespace Rspirolazzi\MultiCompany\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class MultiCompany implements Scope
{

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('company_id', $model->company_id);
    }
}