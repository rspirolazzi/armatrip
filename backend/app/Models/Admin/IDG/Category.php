<?php

namespace App\Models\Admin\IDG;

use App\Models\Admin\Category as Model;

class Category extends Model
{
    public function scopeAllowedToSell($query)
    {
        return $query->where('allowed_to_sell', '=', 1);
    }
    public function scopeAllowedToSellParents($query)
    {
        return $query->where('allowed_to_sell', '=', 1)
            ->whereNull('parent_id');
    }
}