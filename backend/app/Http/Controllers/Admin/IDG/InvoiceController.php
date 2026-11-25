<?php

namespace App\Http\Controllers\Admin\IDG;

use App\Criteria\Admin\StoreByUserCriteria;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Admin\CreateInvoiceRequest;
use App\Http\Requests\Admin\UpdateInvoiceRequest;
use App\Models\Admin\Invoice;
use App\Models\Admin\InvoiceItem;
use App\Models\Admin\Product;
use App\Repositories\Admin\InvoiceRepository;
use Auth;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Validator\Exceptions\ValidatorException;
use Response;

class InvoiceController extends AppBaseController
{

    /** @var  InvoiceRepository */
    private $invoiceRepository;

    public function __construct(InvoiceRepository $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * Display a listing of the Invoice.
     *
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        $this->invoiceRepository->pushCriteria(new RequestCriteria($request));
        $this->invoiceRepository->pushCriteria(new StoreByUserCriteria($request));
        $invoices = $this->invoiceRepository->paginate(25);

        return view('admin.invoices.index')
            ->with('invoices', $invoices);
    }

    /**
     * Show the form for creating a new Invoice.
     *
     * @return Response
     */
    public function create()
    {
        $products = Product::where('allowed_to_sell', 1)->get();
        return view('admin.invoices.create')
            ->with('products', $products);
    }

    /**
     * Store a newly created Invoice in storage.
     *
     * @param CreateInvoiceRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreateInvoiceRequest $request)
    {
        DB::beginTransaction();
        try {
            Log::info('Se comienza a generar el borrador de el pedido interno');
            $order_number = Invoice::generateOrderNumber(Auth::user()->store_id);
            $invoice = Invoice::create([
                'status' => Invoice::$STATUS_DRAFT,
                'user_id' => Auth::user()->id,
                'model_type' => Invoice::class,
                'store_id' => Auth::user()->store_id,
                'order_number' => $order_number,
            ]);
            Log::debug('Se genero con éxito el pedido interno', $invoice->toArray());
            $items = $request->get('items');
            $itemsFiltered = collect($items)->map(function ($values, $model_type) {
                $temp = collect($values)->filter(function ($qty) {
                    return (int)$qty > 0;
                })->all();
                return $temp;
            })->filter(function ($values) {
                return sizeof($values) > 0;
            });
            if (sizeof($itemsFiltered) === 0) {
                throw new ValidatorException('No hay cantidades agregadas');
            }
            $itemsFiltered->each(function ($values, $model_type) use ($invoice) {
                collect($values)->each(function ($qty, $id) use ($invoice, $model_type) {
                    dd($id, $qty);
                    $invoiceItem = new InvoiceItem([
                        'model_type' => $model_type,
                        'model_id' => $id,
                        'qty' => $qty,
                    ]);
                    $invoice->items()->save($invoiceItem);
                });
            });
            DB::commit();
            Log::info('Se completo con éxito la creacion del pedido', $invoice->toArray());
            Flash::success('El pedido se genero con éxito');
            return redirect(route('admin.invoices.confirm', [$invoice->id]));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ocurrio un error inesperado al intentar generar el pedido interno', ['message' => $e->getMessage()]);
            Flash::error('Ocurrio un error al generar el pedido');
            return redirect(route('admin.invoices.create'));
        }
        return redirect(route('admin.invoices.index'));
    }

    /**
     * Show the form for editing the specified Invoice.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $invoice = $this->invoiceRepository->findWithoutFail($id);

        if (empty($invoice)) {
            Flash::error('Pedido interno no encontrado');

            return redirect(route('admin.invoices.index'));
        }
        $products = Product::where('allowed_to_sell', 1)->get();
        return view('admin.invoices.edit')
            ->with('invoice', $invoice)
            ->with('products', $products);;
    }

    /**
     * Show the form for editing the specified Invoice.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function confirm($id)
    {
        $invoice = $this->invoiceRepository->findWithoutFail($id);

        if (empty($invoice)) {
            Flash::error('Pedido interno no encontrado');

            return redirect(route('admin.invoices.index'));
        }

        return view('admin.invoices.confirm')->with('invoice', $invoice);
    }

    /**
     * Show the form for editing the specified Invoice.
     *
     * @param  int $id
     *
     * @param UpdateInvoiceRequest $request
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function confirm_invoice($id, UpdateInvoiceRequest $request)
    {
        $invoice = $this->invoiceRepository->findWithoutFail($id);

        if (empty($invoice)) {
            Flash::error('Pedido interno no encontrado');

            return redirect(route('admin.invoices.index'));
        }

        $invoice = $this->invoiceRepository->update([
            'comment' => $request->get('comment'),
            'status' => Invoice::$STATUS_CONFIRMED,
            'sent_at' => Carbon::now(),
        ], $id);

        Flash::success('El pedido interno se actualizo con éxito.');

        return redirect(route('admin.invoices.index'));
    }

    /**
     * Update the specified Invoice in storage.
     *
     * @param  int $id
     * @param UpdateInvoiceRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id, UpdateInvoiceRequest $request)
    {
        Log::info('Se intentara actualizar la invoice ' . $id);
        DB::beginTransaction();
        $invoice = $this->invoiceRepository->findWithoutFail($id);
        try {
            if (empty($invoice)) {
                Flash::error('Pedido interno no encontrado');
                return redirect(route('admin.invoices.index'));
            }
            $itemsFiltered = $request->get('items');
            collect($itemsFiltered)->each(function ($values, $model_type) use ($invoice) {
                collect($values)->each(function ($qty, $id) use ($invoice, $model_type) {
                    $invoiceItemIndex = $invoice->items->search(function ($item) use ($id, $model_type) {
                        return ($item->model_id == $id && $item->model_type == $model_type);
                    });
                    $qty = (int)$qty;
                    if ($invoiceItemIndex !== false) {
                        if ($qty > 0) {
                            $invoice->items[$invoiceItemIndex]->qty = $qty;
                            $invoice->items[$invoiceItemIndex]->save();
                            Log::debug('Se actualiza la cantidad para el item', $invoice->items[$invoiceItemIndex]->toArray());
                        } else {
                            Log::debug('Se elmina el producto en el pedido', $invoice->items[$invoiceItemIndex]->toArray());
                            $invoice->items[$invoiceItemIndex]->delete();
                        }
                    } else if ($qty > 0) {
                        $invoiceItem = new InvoiceItem([
                            'model_type' => $model_type,
                            'model_id' => $id,
                            'qty' => $qty,
                        ]);
                        $invoice->items()->save($invoiceItem);
                        Log::debug('Se agrega un nuevo item para el pedido', $invoiceItem->toArray());
                    }
                });
            });
            DB::commit();
            Log::info('Se completo con éxito la actualización del pedido', $invoice->toArray());
            Flash::success('El pedido interno se actualizo con éxito.');
            return redirect(route('admin.invoices.confirm', $invoice->id));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ocurrio un error al intentar actualizar el pedido interno', ['message' => $e->getMessage()]);
            Log::error('Ocurrio un error al intentar actualizar el pedido interno', $invoice->toArray());
            Flash::error('Ocurrio un error al intentar actualizar el pedido interno');
            return redirect(route('admin.invoices.edit', $invoice->id));
        }
    }

    /**
     * Display the specified Invoice.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $invoice = $this->invoiceRepository->findWithoutFail($id);

        if (empty($invoice)) {
            Flash::error('El pedido interno no se encontró');

            return redirect(route('admin.invoices.index'));
        }

        return view('admin.invoices.show')->with('invoice', $invoice);
    }

    /**
     * Remove the specified Invoice from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $invoice = $this->invoiceRepository->findWithoutFail($id);

        if (empty($invoice)) {
            Flash::error('El pedido interno no se encontró');

            return redirect(route('admin.invoices.index'));
        }

        $this->invoiceRepository->delete($id);

        Flash::success('El pedido interno borró con éxito.');

        return redirect(route('admin.invoices.index'));
    }
}
