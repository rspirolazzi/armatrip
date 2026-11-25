<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateDateRangeRequest;
use App\Http\Requests\Admin\UpdateDateRangeRequest;
use App\Repositories\Admin\DateRangeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class DateRangeController extends AppBaseController
{
    /** @var  DateRangeRepository */
    private $dateRangeRepository;

    public function __construct(DateRangeRepository $dateRangeRepo)
    {
        $this->dateRangeRepository = $dateRangeRepo;
    }

    /**
     * Display a listing of the DateRange.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->dateRangeRepository->pushCriteria(new RequestCriteria($request));
        $dateRanges = $this->dateRangeRepository->all();

        return view('admin.date_ranges.index')
            ->with('dateRanges', $dateRanges);
    }

    /**
     * Show the form for creating a new DateRange.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.date_ranges.create');
    }

    /**
     * Store a newly created DateRange in storage.
     *
     * @param CreateDateRangeRequest $request
     *
     * @return Response
     */
    public function store(CreateDateRangeRequest $request)
    {
        $input = $request->all();

        $dateRange = $this->dateRangeRepository->create($input);

        Flash::success('El rango de fecha se guardó con éxito.');

        return redirect(route('admin.dateRanges.index'));
    }

    /**
     * Display the specified DateRange.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $dateRange = $this->dateRangeRepository->findWithoutFail($id);

        if (empty($dateRange)) {
            Flash::error('El rango de fecha no se encontró');

            return redirect(route('admin.dateRanges.index'));
        }

        return view('admin.date_ranges.show')->with('dateRange', $dateRange);
    }

    /**
     * Show the form for editing the specified DateRange.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $dateRange = $this->dateRangeRepository->findWithoutFail($id);

        if (empty($dateRange)) {
            Flash::error('El rango de fecha no se encontró');

            return redirect(route('admin.dateRanges.index'));
        }

        return view('admin.date_ranges.edit')->with('dateRange', $dateRange);
    }

    /**
     * Update the specified DateRange in storage.
     *
     * @param  int              $id
     * @param UpdateDateRangeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDateRangeRequest $request)
    {
        $dateRange = $this->dateRangeRepository->findWithoutFail($id);

        if (empty($dateRange)) {
            Flash::error('El rango de fecha no se encontró');

            return redirect(route('admin.dateRanges.index'));
        }

        $dateRange = $this->dateRangeRepository->update($request->all(), $id);

        Flash::success('El rango de fecha se actualizó con éxito.');

        return redirect(route('admin.dateRanges.index'));
    }

    /**
     * Remove the specified DateRange from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $dateRange = $this->dateRangeRepository->findWithoutFail($id);

        if (empty($dateRange)) {
            Flash::error('El rango de fecha no se encontró');

            return redirect(route('admin.dateRanges.index'));
        }

        $this->dateRangeRepository->delete($id);

        Flash::success('El rango de fecha se eliminó con éxito.');

        return redirect(route('admin.dateRanges.index'));
    }
}
