<?php
/**
 * Created by PhpStorm.
 * User: rodrigos
 * Date: 2019-08-24
 * Time: 1:39 p.m.
 */

namespace App\Models\Admin\IDG;

use Illuminate\Database\Eloquent\Model;

class Mediable extends Model
{
    protected $table = "mediables";
    public function getModelAttribute()
    {
        $model_name = $this->mediable_type;
        $model = $model_name::find($this->mediable_id);
        return $model;
    }
}