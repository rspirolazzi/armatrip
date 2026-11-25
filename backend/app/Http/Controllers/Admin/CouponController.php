<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Admin\CreateCouponRequest;
use App\Http\Requests\Admin\UpdateCouponRequest;
use App\Repositories\Admin\CouponRepository;
use Coupons;
use Flash;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CouponController extends AppBaseController
{
    /** @var  CouponRepository */
    private $couponRepository;

    public function __construct(CouponRepository $couponRepo)
    {
        $this->couponRepository = $couponRepo;
    }

    public static function getDefaultsHS()
    {
        $result = [];
        for ($i = 0; $i <= 23; $i++) {
            foreach ([0, 30] as $j) {
                $hour = str_pad($i, 2, '0', STR_PAD_LEFT);
                $min = str_pad($j, 2, '0', STR_PAD_LEFT);
                $result["{$hour}:{$min}:00"] = "{$hour}:{$min}";
            }
        }
        return $result;
    }

    /**
     * Display a listing of the Coupon.
     *
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        $this->couponRepository->pushCriteria(new RequestCriteria($request));
        $this->couponRepository->scopeQuery(function ($query) {
            return $query
                ->orderBy('enabled', 'desc')
                ->orderBy('id', 'desc');
        });
        $coupons = $this->couponRepository->paginate(50);

        return view('admin.coupons.index')
            ->with('coupons', $coupons);
    }

    /**
     * Show the form for creating a new Coupon.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created Coupon in storage.
     *
     * @param CreateCouponRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreateCouponRequest $request)
    {
        $input = $request->all();
        $coupon = $this->couponRepository->create($input);

        Flash::success('Cupón se guardó con éxito.');

        return redirect(route('admin.coupons.index'));
    }

    /**
     * Display the specified Coupon.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $coupon = $this->couponRepository->findWithoutFail($id);

        if (empty($coupon)) {
            Flash::error('Cupón no encontrado');

            return redirect(route('admin.coupons.index'));
        }

        return view('admin.coupons.show')->with('coupon', $coupon);
    }

    /**
     * Show the form for editing the specified Coupon.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $coupon = $this->couponRepository->findWithoutFail($id);

        if (empty($coupon)) {
            Flash::error('Cupón no encontrado');

            return redirect(route('admin.coupons.index'));
        }

        return view('admin.coupons.edit')->with('coupon', $coupon);
    }

    /**
     * Update the specified Coupon in storage.
     *
     * @param  int $id
     * @param UpdateCouponRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id, UpdateCouponRequest $request)
    {
        $coupon = $this->couponRepository->findWithoutFail($id);
        if (empty($coupon)) {
            Flash::error('Cupón no encontrado');

            return redirect(route('admin.coupons.index'));
        }
        $input = $request->all();
        if (!isset($input['hs_start_at'])) {
            $input['hs_start_at'] = null;
        }
        if (!isset($input['hs_end_at'])) {
            $input['hs_end_at'] = null;
        }
        $coupon = $this->couponRepository->update($input, $id);

        Flash::success('Cupón se actualizó con éxito.');

        return redirect(route('admin.coupons.index'));
    }

    /**
     * Remove the specified Coupon from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $coupon = $this->couponRepository->findWithoutFail($id);

        if (empty($coupon)) {
            Flash::error('Cupón no encontrado');

            return redirect(route('admin.coupons.index'));
        }

        $this->couponRepository->delete($id);

        Flash::success('Cupón deleted con éxito.');

        return redirect(route('admin.coupons.index'));
    }
}
