<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasVideo
{

    /**
     * Get the "video" relationship.
     *
     * @return MorphToMany
     */
    public function videos()
    {
        return $this->hasMany(config('media.video.model'), 'model_id');
    }

    /**
     * Attach video
     *
     * @param mixed $video
     * @return void
     */
    public function attachVideo($video)
    {

        $embed = \Embed::make($video->url)->parseUrl();
        if ($embed) {
            $video->type = $embed->getProvider()->name;
        }
        $video->model_type = self::class;
        $this->videos()->save($video);
    }
}
