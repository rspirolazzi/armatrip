<?php

namespace App\Providers;

use App\Models\Admin\Brand;
use App\Models\Admin\Reservation;
use App\Models\Admin\Store;
use Form;
use function GuzzleHttp\Psr7\mimetype_from_filename;
use Html;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class HtmlMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Html::component('box', 'components.dashboard.box', ['title' => '', 'content' => '', 'type' => 'info', 'class' => 'bg-aqua']);

        $this->registerFaCheck();
        $this->registerFaActive();
        $this->registerLinkWithPermissions();
        $this->registerActionsNav();
        $this->registerInputImage();
        $this->registerIconForMedia();
        $this->registerDownloadLinkForMedia();
        $this->registerFormFilter();
    }

    protected function registerIconForMedia()
    {
        Form::macro('iconForMedia', function ($multimedia, $options = []) {
            $title = isset($options['title']) ? $options['title'] : '';
            $id = isset($options['id']) ? $options['id'] : 0;
            $preview_modal = isset($options['preview_modal']) ? $options['preview_modal'] : false;
            $height = isset($options['height']) ? $options['height'] : '150px';
            $src = <<<HTML
<img style="max-height: $height" src="/img/no_image.png" alt="$title"/>
HTML;
            $file = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR. $multimedia);
            if (file_exists($file)) {
                $type = mimetype_from_filename($file);
                switch ($type) {
                    case 'application/pdf':
                        $src = <<<HTML
<img style="height: $height" src="/img/pdf_200x200.png" alt="$title"/>
HTML;
                        break;
                    case 'image/png':
                    case 'image/jpeg':
                        $src = <<<HTML
<a data-toggle="modal" href="#myModal_$id"><img style="max-height: $height" src="$multimedia" alt="$title"/></a>
<div class="modal fade" id="myModal_$id" tabindex="-1" role="dialog" aria-labelledby="$title">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">$title</h4>
            </div>
            <div class="modal-body text-center">
                <img style="height: 100%" src="$multimedia"
                     alt="$title"/>
            </div>
        </div>
    </div>
</div>
HTML;
                        break;
                    default:
                        /*var_dump($options);
                        echo 'A', $icon;
                        die($type);*/
                        Log::debug('[iconForMedia] No se encontro multimedia para este producto', array_merge(['multimedia' => $multimedia], $options));
                        return $src;
                        break;
                }
            }
            return $src;
        });
    }

    protected function registerDownloadLinkForMedia()
    {
        Form::macro('downloadLinkForMedia', function ($multimedia, $options = []) {
            $title = isset($options['title']) ? $options['title'] : '';
            $id = isset($options['id']) ? $options['id'] : 0;
            $preview_modal = isset($options['preview_modal']) ? $options['preview_modal'] : false;
            $height = isset($options['height']) ? $options['height'] : '150px';
            $src = "";
            $dir_multimedia = '/multimedia/';
            //$file = public_path("$dir_multimedia$multimedia");
            $file = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR. $multimedia);
            if (file_exists($file)) {
                $type = mimetype_from_filename($file);
                switch ($type) {
                    case 'application/pdf':
                        $src = <<<HTML
<a target="_blank" alt="Descargar $title" title="Descargar $title" download href="$dir_multimedia$multimedia" class="btn btn-default btn-block" role="button"><i class="fa fa-download"></i></a>
HTML;
                        break;
                    case 'image/png':
                    case 'image/jpeg':
                        $src = <<<HTML
<a target="_blank" alt="Descargar $title" title="Descargar $title" download href="$dir_multimedia$multimedia" class="btn btn-default btn-block" role="button"><i class="fa fa-download"></i></a>
HTML;
                        HTML;
                        break;
                    default:
                        Log::debug('[downloadLinkForMedia] No se encontro multimedia para este producto', array_merge(['multimedia' => $multimedia], $options));

                        break;
                }
            } else {
                $src = <<<HTML
<a target="_blank" alt="Descargar $title" title="Descargar $title" href="$multimedia" class="btn btn-default btn-block" role="button"><i class="fa fa-download"></i></a>
HTML;
            }
            return $src;
        });
    }

    protected function registerFaCheck()
    {
        Form::macro('faCheck', function ($check) {
            $class = $check ? "check-square-o" : "square-o";
            return <<<HTML
<i class="fa fa-$class"></i>
HTML;
        });
    }

    protected function registerFaActive()
    {
        Form::macro('faActive', function ($check) {
            $class = $check ? "check" : "ban";
            $label =$check ? "success" : "danger";
            return <<<HTML
            <span class="label label-$label"><i class="fa fa-$class"></i></span>
HTML;
        });
    }

    protected function registerActionsNav()
    {
        Html::component('actionsNav', 'components.html.actions', ['actions']);
    }
    protected function registerInputImage()
    {
        Html::component('inputImage', 'components.input.image', ['title' => '','name' => '', 'model' => '', 'group' => 'image', 'helper'=>'']);
    }
    protected function _registerFormFilter()
    {
        Html::component('formFilter', 'components.html.filter', ['route','placeholder']);
    }

    protected function registerLinkWithPermissions()
    {
        Html::component('iconLinkWithPermissions', 'components.html.iconLink', ['icon', 'route', 'params', 'attributes']);
        //Html::component('linkWithPermissions', 'components.html.link', ['name', 'value', 'attributes']);
        /*Html::macro('linkWithPermissions', function($html,$url, $permission, $attributes=[]){
            if(Auth::user()->hasPermissionTo($permission)){
                return link_to($url, $html, $attributes);
            }
        });*/
    }
    protected function registerFormFilter()
    {
        Html::component('formFilter', 'components.html.filter', ['route', 'placeholder']);
    }
}
