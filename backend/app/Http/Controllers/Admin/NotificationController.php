<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateNotificationRequest;
use App\Http\Requests\Admin\UpdateNotificationRequest;
use App\Models\Admin\Package;
use App\Models\Admin\Product;
use App\Models\Store;
use App\Repositories\Admin\NotificationRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class NotificationController extends AppBaseController
{
    public static $ACTION_SELECT = 'select';
    public static $ACTION_UN_SELECT = 'unselect';

    /** @var  NotificationRepository */
    private $notificationRepository;

    public function __construct(NotificationRepository $notificationRepo)
    {
        $this->notificationRepository = $notificationRepo;
    }

    /**
     * Display a listing of the Notification.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->notificationRepository->pushCriteria(new RequestCriteria($request));
        $notifications = $this->notificationRepository->paginate(25);

        return view('admin.notifications.index')
            ->with('notifications', $notifications);
    }

    /**
     * Show the form for creating a new Notification.
     *
     * @return Response
     */
    public function create()
    {
        $products = $this->getProductsForOptions();
        return view('admin.notifications.create')
            ->with('products', $products)
            ->with('urls', $this->_getUrls());
    }

    /**
     * Store a newly created Notification in storage.
     *
     * @param CreateNotificationRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreateNotificationRequest $request)
    {
        $inputs = $this->processRequest($request);

        $notification = $this->notificationRepository->create($inputs);

        Flash::success('Notificación se guardó con éxito.');

        return redirect(route('admin.notifications.index'));
    }

    protected function getProductsForOptions()
    {
        $products = Product::where('active', 1)
            ->get()
            ->sortBy('category.order')
            ->groupBy('category.id')
            ->map(function ($result) {
                return [
                    'category' => $result[0]['category']->toArray(),
                    'products' => $result->sortBy('order')->toArray(),
                ];
            });
        return $products;
    }

    /**
     * Display the specified Notification.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $notification = $this->notificationRepository->findWithoutFail($id);

        if (empty($notification)) {
            Flash::error('Notificación no se encontró');

            return redirect(route('admin.notifications.index'));
        }

        return view('admin.notifications.show')->with('notification', $notification);
    }

    protected function _getUrls()
    {
        $result = [];
        $packages = Package::all();
        $packages->each(function ($package) use (&$result) {
            $_result = [
                ['url' => "/package/{$package->code}", 'name' => $package->name, 'active' => $package->active],
                ['url' => "/package/{$package->code}/services", 'name' => $package->name, 'active' => $package->active],
                ['url' => "/package/{$package->code}/confirm", 'name' => $package->name, 'active' => $package->active],
            ];
            $result = array_merge($result, $_result);
        });
        $stores = Store::all();
        $stores->each(function ($store) use (&$result) {
            $_result = [
                ['url' => "/packages/{$store->code}", 'name' => $store->name, 'active' => $store->active],
            ];
            $result = array_merge($result, $_result);
        });
        $result = array_merge($result, [
            ['url' => '/', 'name' => 'Inicio', 'active' => true],
            ['url' => '/stores', 'name' => 'Empresas', 'active' => true],
            ['url' => '/register', 'name' => 'Registro', 'active' => true],
            ['url' => '/select-date', 'name' => 'Seleccionar una fecha', 'active' => true],
            ['url' => '/activation', 'name' => 'Activación', 'active' => true],
            ['url' => '/packages/:store_code', 'name' => 'Paquetes', 'active' => true],
            ['url' => '/package/:code', 'name' => 'Paquete', 'active' => true],
            ['url' => '/package/:code/services', 'name' => 'Servicios', 'active' => true],
            ['url' => '/package/:code/confirm', 'name' => 'Confirmación', 'active' => true],
            ['url' => '/checkout', 'name' => 'Checkout', 'active' => true],
            ['url' => '/finish', 'name' => 'Fin', 'active' => true],
            ['url' => '/contact', 'name' => 'Contacto', 'active' => true],
            ['url' => '/register/contact', 'name' => 'Contacto Pais', 'active' => true],
            ['url' => '/thanks', 'name' => 'Gracias', 'active' => true],
        ]);

        return collect($result);
    }


    /**
     * Show the form for editing the specified Notification.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {

        $notification = $this->notificationRepository->findWithoutFail($id);
        $products = $this->getProductsForOptions();


        if (empty($notification)) {
            Flash::error('Notificación no se encontró');

            return redirect(route('admin.notifications.index'));
        }

        return view('admin.notifications.edit')
            ->with('notification', $notification)
            ->with('products', $products)
            ->with('urls', $this->_getUrls());
    }

    /**
     * Update the specified Notification in storage.
     *
     * @param  int $id
     * @param UpdateNotificationRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id, UpdateNotificationRequest $request)
    {
        $notification = $this->notificationRepository->findWithoutFail($id);

        if (empty($notification)) {
            Flash::error('Notificación no se encontró');

            return redirect(route('admin.notifications.index'));
        }

        $inputs = $this->processRequest($request);

        $notification = $this->notificationRepository->update($inputs, $id);

        Flash::success('Notificación se actualizó con éxito.');

        return redirect(route('admin.notifications.index'));
    }

    /**
     * Remove the specified Notification from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $notification = $this->notificationRepository->findWithoutFail($id);

        if (empty($notification)) {
            Flash::error('Notificación no se encontró');

            return redirect(route('admin.notifications.index'));
        }

        $this->notificationRepository->delete($id);

        Flash::success('Notificación se eliminó con éxito.');

        return redirect(route('admin.notifications.index'));
    }

    protected function processRequest(Request $request)
    {
        Log::debug('processRequest:Begin', $request->toArray());
        $values = $request->only("code", "title", "message", "duration", "order", "uri", "type", "is_actions", "is_custom", "active");
        $values['rules'] = null;
        if ($values['is_actions'] == 1) {
            $values_action = $request->get("is_actions_action", []);
            $values_product = $request->get("is_actions_product", []);
            $values_order = $request->get("is_actions_order", []);
            $values['rules'] = collect($values_action)
                ->map(function ($action, $index) use ($values_product, $values_order) {
                    if (!isset($values_product[$index])) {
                        return null;
                    }
                    $order = 1;
                    if (isset($values_order[$index])) {
                        $order = $values_order[$index];
                    }
                    $product = Product::find($values_product[$index]);
                    return [
                        'action' => ['id' => $action, 'name' => ''],
                        'product' => ['id' => $product->id, 'name' => $product->name],
                        'order' => $order,
                    ];
                })
                ->filter(function ($row) {
                    return !is_null($row);
                })->toArray();
            $values['is_actions'] = collect($values['rules'])->count() > 0;
            $values['rules'] = json_encode($values['rules']);
        }
        Log::debug('processRequest:End', $values);
        return $values;
    }
}
