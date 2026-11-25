<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Traits\AttachMedia;
use App\Http\Requests\Admin\CreateStoreRequest;
use App\Http\Requests\Admin\UpdateStoreRequest;
use App\Models\Admin\Province;
use App\Models\Admin\SettingManager;
use App\Models\Item;
use App\Models\Store;
use App\Repositories\Admin\StoreRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class StoreController extends AppBaseController
{
    use AttachMedia;

    /** @var  StoreRepository */
    private $storeRepository;

    public function __construct(StoreRepository $storeRepo)
    {
        $this->storeRepository = $storeRepo;
    }

    /**
     * Display a listing of the Store.
     *
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        $this->storeRepository->pushCriteria(new RequestCriteria($request));

        $stores = $this->storeRepository->paginate(25);
        $turismo_individual = SettingManager::get('store_code_individual', 'turismo_individual');
        return view('admin.stores.index')
            ->with('turismo_individual', $turismo_individual)
            ->with('stores', $stores);
    }

    /**
     * Show the form for creating a new Store.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.stores.create');
    }

    /**
     * Store a newly created Store in storage.
     *
     * @param CreateStoreRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreateStoreRequest $request)
    {
        $input = $request->all();

        $store = $this->storeRepository->create($input);
        $this->saveIcon($request, $store);

        Flash::success('Sucursal guardada con éxito.');

        return redirect(route('admin.stores.index'));
    }

    private function saveIcon(Request $request, Store $store)
    {
        $this->uploadImage($request, 'icon', $store, Store::$GROUP_ICON, ['jpg', 'jpeg', 'png']);

        // La imagen subida reemplaza a la que se cargaba en base64.
        if ($store->icon && ($request->get('_delete_icon_base64') || $store->load('media')->hasMedia(Store::$GROUP_ICON))) {
            $store->icon = null;
            $store->save();
        }
    }

    /**
     * Display the specified Store.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $store = $this->storeRepository->findWithoutFail($id);

        if (empty($store)) {
            Flash::error('Sucursal no encontrada');

            return redirect(route('admin.stores.index'));
        }

        return view('admin.stores.show')->with('store', $store);
    }

    /**
     * Show the form for editing the specified Store.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $store = $this->storeRepository->findWithoutFail($id);

        if (empty($store)) {
            Flash::error('Sucursal no encontrada');

            return redirect(route('admin.stores.index'));
        }

        return view('admin.stores.edit')->with('store', $store);
    }

    /**
     * Update the specified Store in storage.
     *
     * @param  int $id
     * @param UpdateStoreRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id, UpdateStoreRequest $request)
    {
        $store = $this->storeRepository->findWithoutFail($id);

        if (empty($store)) {
            Flash::error('Sucursal no encontrada');

            return redirect(route('admin.stores.index'));
        }

        $store = $this->storeRepository->update($request->all(), $id);
        $this->saveIcon($request, $store);

        Flash::success('Sucursal actualizada con éxito.');

        return redirect(route('admin.stores.index'));
    }

    /**
     * Show the form for editing the specified Store.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function province($id)
    {
        $store = $this->storeRepository->findWithoutFail($id);

        if (empty($store)) {
            Flash::error('Sucursal no encontrada');

            return redirect(route('admin.stores.index'));
        }
        $provinces = Province::whereNull('parent_id')->get();

        return view('admin.stores.province')
            ->with('store', $store)
            ->with('provinces', $provinces);
    }

    /**
     * Update the specified Store in storage.
     *
     * @param  int $id
     * @param Request $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function provinceUpdate($id, Request $request)
    {
        $store = $this->storeRepository->findWithoutFail($id);

        if (empty($store)) {
            Flash::error('Sucursal no encontrada');

            return redirect(route('admin.stores.index'));
        }
        $store->provinces()->sync($request->get('province_id', []));

        Flash::success('Sucursal actualizada con éxito.');

        return redirect(route('admin.stores.province', [$store->id]));
    }

    /**
     * Show the form for editing the specified Store.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function deliveryArea($id)
    {
        $store = $this->storeRepository->findWithoutFail($id);

        if (empty($store)) {
            Flash::error('Sucursal no encontrada');

            return redirect(route('admin.stores.index'));
        }

        return view('admin.stores.deliveryArea')
            ->with('store', $store);
    }

    /**
     * Update the specified Store in storage.
     *
     * @param  int $id
     * @param UpdateStoreRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function updateDeliveryArea($id, Request $request)
    {
        $store = $this->storeRepository->findWithoutFail($id);

        if (empty($store)) {
            Flash::error('Sucursal no encontrada');
            return redirect(route('admin.stores.index'));
        }
        $map_area = $request->get('map_area');

        $this->storeRepository->update(['map_area' => $map_area], $id);
        Flash::success('El area de delivery se actualizó con éxito.');

        return redirect(route('admin.stores.edit', [$id]));
    }

    /**
     * Remove the specified Store from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $store = $this->storeRepository->findWithoutFail($id);

        if (empty($store)) {
            Flash::error('Sucursal no encontrada');

            return redirect(route('admin.stores.index'));
        }

        $this->storeRepository->delete($id);

        Flash::success('Sucursal deleted con éxito.');

        return redirect(route('admin.stores.index'));
    }

    /**
     * Show the form for editing the specified Store.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function delivery($id)
    {
        $store = $this->storeRepository->findWithoutFail($id);

        if (empty($store)) {
            Flash::error('Sucursal no encontrada');

            return redirect(route('admin.stores.delivery'));
        }

        return view('admin.stores.delivery')
            ->with('store', $store);
    }

    /**
     * Update the specified Store in storage.
     *
     * @param  int $id
     * @param Request $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function storeDelivery($id, Request $request)
    {
        $store = $this->storeRepository->findWithoutFail($id);


        $delivery_times = [];
        //if()

        if (empty($store)) {
            Flash::error('Sucursal no encontrada');

            return redirect(route('admin.stores.index'));
        }

        if ($request->get('type') == Store::$DELIVERY_TIME_ALL) {
            $time_all = $request->get('delivery_time')[$request->get('type')];
            if (is_null($time_all['start_hour']) || is_null($time_all['start_min']) || is_null($time_all['end_hour']) || is_null($time_all['end_min'])) {
                Flash::error('Debes seleccionar al menos un tipo de horario');
                return redirect(route('admin.stores.delivery'));
            }
            $delivery_times['type'] = $request->get('type');
            $delivery_times[$request->get('type')] = $time_all;
        } else if ($request->get('type') == Store::$DELIVERY_TIME_CUSTOM) {
            $time_custom = $request->get('delivery_time')[$request->get('type')];
            $delivery_times['type'] = $request->get('type');
            foreach ($time_custom as $index => $value) {
                if (is_null($value['start_hour']) || is_null($value['start_min']) || is_null($value['end_hour']) || is_null($value['end_min'])) {
                    //Flash::error('Debes seleccionar al menos un tipo de horario');
                    //return redirect(route('admin.stores.delivery'));
                } else {
                    $delivery_times[$request->get('type')][$index] = $value;
                }
            }
        } else {
            Flash::error('Debes seleccionar al menos un tipo de horario');
            return redirect(route('admin.stores.delivery'));
        }
        $this->storeRepository->update(['deliveryTimes' => json_encode($delivery_times)], $id);
        Flash::success('El horario se agrego con éxito.');
        if ($request->get('submit') == 'add_and_continue') {
            return redirect(route('admin.stores.delivery', [$id]));
        }
        return redirect(route('admin.stores.edit', [$id]));
    }

    public function items($id)
    {
        $store = $this->storeRepository->findWithoutFail($id);

        if (empty($store)) {
            Flash::error('Sucursal no encontrada');

            return redirect(route('admin.stores.delivery'));
        }
        $items = Item::all();
        return view('admin.stores.items')
            ->with('store', $store)
            ->with('items', $items);
    }

    /**
     * Update the specified Store in storage.
     *
     * @param  int $id
     * @param Request $request
     *
     * @return Response
     */
    public function updateItems($id, Request $request)
    {
        try {
            $store = $this->storeRepository->findWithoutFail($id);

            if (empty($store)) {
                Flash::error('Sucursal no encontrada');

                return redirect(route('admin.stores.index'));
            }
            $itemsToDelete = $store->items->whereNotIn('id', $request->get('item'));
            $itemsToIgnore = $store->items->whereIn('id', $request->get('item'));

            foreach ($itemsToDelete->all() as $toDelete) {
                Log::debug('Quitando sabor', $toDelete->toArray());
                $store->items()->detach($toDelete->id);
            }
            if ($request->get('item')) {
                foreach ($request->get('item') as $itemId) {
                    if (is_null($itemsToIgnore->firstWhere('id', $itemId))) {
                        Log::debug('Agrenado sabor id', ['id' => $id]);
                        $store->items()->attach($itemId);
                    }
                }
            }
            Flash::success('Los sabores fueron excluidos con éxito');
        } catch (\Exception $ex) {
            Log::error('Ocurrio un error al intentar excluir los sabores', ['message' => $ex->getMessage(), 'file' => $ex->getFile(), 'line' => $ex->getLine()]);
            Flash::error('Ocurrio un error al intentar excluir los sabores');
        }
        return redirect(route('admin.stores.items', [$id]));
    }
}
