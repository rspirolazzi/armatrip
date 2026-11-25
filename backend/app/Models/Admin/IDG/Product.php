<?php

namespace App\Models\Admin\IDG;

use App\Models\Admin\Product as Model;
use Illuminate\Support\Facades\Storage;


class Product extends Model
{
    public function getOptionsImagePrimaryAttribute()
    {
        return $this->getMultimedia($this->image_primary);
    }

    public function getOptionsImageSecondaryAttribute()
    {
        return $this->getMultimedia($this->image_secondary);
    }

    public function getOptionsImageTertiaryAttribute()
    {
        return $this->getMultimedia($this->image_tertiary);
    }

    public function getOptionsIconAttribute()
    {
        return $this->getMultimedia($this->icon);
    }

    protected function getMultimedia($value)
    {
        if ($value) {
            $src =  $value; 
            $file = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR. $src);
            if (file_exists($file)) {
                $options = getimagesize($file);
                return array_merge([
                    'source' => $src,
                    'type' => 'file',
                ], $options);
            } else {
                $options = [];
                return array_merge([
                    'source' => $value,
                    'type' => 'url',
                ], $options);
            }
        }
        return null;
    }
}
