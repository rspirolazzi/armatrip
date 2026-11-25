<?php
/**
 * Created by PhpStorm.
 * User: rodrigos
 * Date: 2019-08-24
 * Time: 1:23 p.m.
 */

namespace App\Models\Admin\IDG;

use \Optix\Media\Models\Media as OptixMedia;

class Media extends OptixMedia
{
    /**
     * Get the "media" relationship.
     *
     * @return MorphToMany
     */
    public function mediable()
    {
        return $this->hasOne(Mediable::class);
    }

}