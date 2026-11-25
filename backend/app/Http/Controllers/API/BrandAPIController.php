<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\Admin\Brand;
use App\Repositories\API\BrandRepository;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class BrandController
 * @package App\Http\Controllers\API
 */
class BrandAPIController extends AppBaseController
{
    /** @var  BrandRepository */
    private $brandRepository;

    public function __construct(BrandRepository $brandRepo)
    {
        $this->brandRepository = $brandRepo;
    }

    /**
     * Display a listing of the Brand.
     * GET|HEAD /brands
     *
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        $this->brandRepository->pushCriteria(new RequestCriteria($request));
        $this->brandRepository->pushCriteria(new LimitOffsetCriteria($request));

        $this->brandRepository->scopeQuery(function ($query) {
            return $query->where('active', 1);
        });
        $brands = $this->brandRepository->all();
        return $this->sendResponse($brands->map(function($brand){
            return [
                'code'=>$brand->code,
                'name'=>$brand->name,
                'link'=>$brand->link,
                'logo_original'=>$brand->getFirstMediaUrl(Brand::$GROUP_LOGO),
                'logo'=>$brand->getFirstMediaUrl(Brand::$GROUP_LOGO, Brand::$IMAGE_CONVERSION_THUMBNAIL),
            ];
        }));
    }
}
