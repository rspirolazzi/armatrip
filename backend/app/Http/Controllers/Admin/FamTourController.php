<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use App\Models\Admin\FamTour;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Webklex\IMAP\Facades\Client;
use Carbon\Carbon;
use App\Repositories\Admin\FamTourRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class FamTourController extends AppBaseController
{

    /** @var  FamTourRepository */
    private $famTourRepository;

    public function __construct(FamTourRepository $famTourRepo)
    {
        $this->famTourRepository = $famTourRepo;
    }


    public function sync(Request $request)
    {
        try {
            $result = $this->famTourRepository->syncEmails();
            Flash::success('Se sincronizaron con éxito '.$result->count().' emails');
        } catch (Exception $e) {
            Flash::error('Ocurrio un error al intentar sincronizar los emails');
            Log::error('Ocurrio un error al intentar sincronizar los emails', [
                'error' => $e->__toString(),
            ]);
        }
        try {
            $result = FamTour::whereNull('from')
                ->where('body', 'like', '%mail%')
                ->get();
            $return = $result->map(function($fam_tour){
                $body = $fam_tour->originalBody;
                $values = $this->famTourRepository->parseBody($body);
                $values['fam_tour'] = $fam_tour;
                return $values;
            });
            $return->each(function($v){
                $fam_tour = $v['fam_tour'];
                if(empty($fam_tour->from) && !empty($v['mail']) ){
                    $fam_tour->from = $v['mail']['value'];
                }
                if(empty($fam_tour->name) && !empty($v['name']) ){
                    $fam_tour->name = $v['name']['value'];
                }
                if(empty($fam_tour->phone) && !empty($v['phone']) ){
                    $fam_tour->phone = $v['phone']['value'];
                }
                if(empty($fam_tour->student) && !empty($v['student']) ){
                    $fam_tour->student = $v['student']['value'];
                }
                if(empty($fam_tour->school) && !empty($v['school']) ){
                    $fam_tour->school = $v['school']['value'];
                }
                if(empty($fam_tour->city) && !empty($v['city']) ){
                    $fam_tour->city = $v['city']['value'];
                }
                $fam_tour->save();
            });
        } catch (Exception $e) {
            dd($e);
            Log::error('Ocurrio un error al intentar obtenter todos los mensajes donde el email es null', [
                'error' => $e->__toString(),
            ]);
        }
        die('A');
        return redirect(route('admin.fam_tours.index'));
    }

    public function update(Request $request)
    {
        $fam_Tour = $this->famTourRepository->findWithoutFail($request->get('id'));
        if (empty($fam_Tour)) {
            Flash::error('Mensaje no encontrado');
            return redirect(route('admin.fam_tours.index'));
        }
        $values = $request->all();
        if(!empty($values['category'])){
            try {
                $this->famTourRepository->tag($fam_Tour, $values['category']);
                Flash::success('La categoría se guardo con éxito para el mensaje de ' . $fam_Tour->name);
            } catch (Exception $e) {
                Flash::error('Ocurrio un error al intentar guardar la categoria');
            }
        }
        if(!empty($values['calendar'])){
            $calendar_date = Carbon::createFromFormat('Y-m-d',$values['calendar']);
            $calendar_date->hour(0)->minute(0)->second(0);
            //dd($calendar_date);
            $google_url = 'https://www.google.com/calendar/render?';
            $params = [
                'action'=>'TEMPLATE',
                'dates'=>$calendar_date->format('Ymd').'/'.$calendar_date->format('Ymd'),
                'text'=>$fam_Tour->name,
                'location'=>'',
                'details'=>$fam_Tour->originalBody . '(Link interno:'. route('admin.fam_tours.show', [$fam_Tour->id]) .')',
            ];
            $values['link_google_calendar'] =  $google_url . collect($params)->map(function ($value, $key) {
                return urlencode($key) . '=' . urlencode($value);
            })->implode('&');
        }
        $fam_Tour = $this->famTourRepository->update($values, $fam_Tour->id);
        return redirect(route('admin.fam_tours.index'));
    }

    public function edit($id)
    {
        $fam_tour = $this->famTourRepository->findWithoutFail($id);

        if (empty($fam_tour)) {
            Flash::error('Mensaje no encontrado');

            return redirect(route('admin.fam_tours.index'));
        }
        $tags = FamTour::existingTags()->map(function($value){
            return $value->name;
        });
        return view('admin.fam_tours.edit')
            ->with('tags', $tags)
            ->with('fam_tour', $fam_tour)
            ;
    }

    public function show($id)
    {
        $fam_tour = $this->famTourRepository->findWithoutFail($id);

        if (empty($fam_tour)) {
            Flash::error('Mensaje no encontrado');

            return redirect(route('admin.fam_tours.index'));
        }
        return view('admin.fam_tours.show')
            ->with('fam_tour', $fam_tour)
            ;
    }

    public function index()
    {
        $items_for_page = 25;
        $this->famTourRepository->scopeQuery(function ($query) {
            return $query->orderBy(config('imap.sort.column', 'date'), config('imap.sort.order', 'desc'));
        });
        $fam_tours = $this->famTourRepository
            ->paginate($items_for_page);
        $tags = FamTour::existingTags()->map(function($value){
            return $value->name;
        });
        return view('admin.fam_tours.index')
        ->with('tags', $tags)
            ->with('fam_tours', $fam_tours)
            ;
    }
}
