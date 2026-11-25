<?php

namespace App\Models;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'url', 'type', 'attributes'
    ];

    /**
     * Get the file extension.
     *
     * @return string
     */
    public function getExtensionAttribute()
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

    /**
     * Get the file type.
     *
     * @return string|null
     */
    public function getTypeAttribute()
    {
        return str_before($this->mime_type, '/') ?? null;
    }

    /**
     * Determine if the file is of the specified type.
     *
     * @param string $type
     * @return bool
     */
    public function isOfType(string $type)
    {
        return $this->type === $type;
    }

    /**
     * Get the url to the file.
     *
     * @param string $conversion
     * @return mixed
     */
    public function getUrl(string $conversion = '')
    {
        // return $this->filesystem()->url($this->getPath($conversion));
    }

    /**
     * Get the full path to the file.
     *
     * @param string $conversion
     * @return mixed
     */
    public function getFullPath(string $conversion = '')
    {
        //return $this->filesystem()->path($this->getPath($conversion));
    }

    /**
     * Get the path to the file on disk.
     *
     * @param string $conversion
     * @return string
     */
    public function getPath(string $conversion = '')
    {
        $directory = $this->getDirectory();

        if ($conversion) {
            $directory .= '/conversions/' . $conversion;
        }

        return $directory . '/' . $this->file_name;
    }

    /**
     * Get the directory for files on disk.
     *
     * @return mixed
     */
    public function getDirectory()
    {
        return $this->getKey();
    }

    /**
     * Get the filesystem where the associated file is stored.
     *
     * @return Filesystem
     */
    public function filesystem()
    {
        return Storage::disk($this->disk);
    }

    public function getVideoHtmlAttribute()
    {
        $embed = \Embed::make($this->url)->parseUrl();
        //dd($embed);
        if (!$embed)
            return '';
        $embed->setAttribute(['class' => "embed-responsive-item"]);
        return $embed->getHtml();
    }

    public function getEmbedAttribute()
    {
        $embed = \Embed::make($this->url)->parseUrl();
        //dd($embed);
        if (!$embed) {

            return [];
        } else {
            return $embed->getProvider();
        }
    }
}
