<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Requests\API\Admin\CreatePackageGroupAPIRequest;
use App\Http\Requests\API\Admin\UpdatePackageGroupAPIRequest;
use App\Models\Admin\PackageGroup;
use App\Repositories\Admin\PackageGroupRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class PackageGroupController
 * @package App\Http\Controllers\API\Admin
 */

class PackageGroupAPIController extends AppBaseController
{
    /** @var  PackageGroupRepository */
    private $packageGroupRepository;

    public function __construct(PackageGroupRepository $packageGroupRepo)
    {
        $this->packageGroupRepository = $packageGroupRepo;
    }

    /**
     * Display a listing of the PackageGroup.
     * GET|HEAD /packageGroups
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->packageGroupRepository->pushCriteria(new RequestCriteria($request));
        $this->packageGroupRepository->pushCriteria(new LimitOffsetCriteria($request));
        $packageGroups = $this->packageGroupRepository->all();

        return $this->sendResponse($packageGroups->toArray(), 'Package Groups retrieved successfully');
    }

    /**
     * Store a newly created PackageGroup in storage.
     * POST /packageGroups
     *
     * @param CreatePackageGroupAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePackageGroupAPIRequest $request)
    {
        $input = $request->all();

        $packageGroup = $this->packageGroupRepository->create($input);

        return $this->sendResponse($packageGroup->toArray(), 'Package Group saved successfully');
    }

    /**
     * Display the specified PackageGroup.
     * GET|HEAD /packageGroups/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var PackageGroup $packageGroup */
        $packageGroup = $this->packageGroupRepository->findWithoutFail($id);

        if (empty($packageGroup)) {
            return $this->sendError('Package Group not found');
        }

        return $this->sendResponse($packageGroup->toArray(), 'Package Group retrieved successfully');
    }

    /**
     * Update the specified PackageGroup in storage.
     * PUT/PATCH /packageGroups/{id}
     *
     * @param  int $id
     * @param UpdatePackageGroupAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePackageGroupAPIRequest $request)
    {
        $input = $request->all();

        /** @var PackageGroup $packageGroup */
        $packageGroup = $this->packageGroupRepository->findWithoutFail($id);

        if (empty($packageGroup)) {
            return $this->sendError('Package Group not found');
        }

        $packageGroup = $this->packageGroupRepository->update($input, $id);

        return $this->sendResponse($packageGroup->toArray(), 'PackageGroup updated successfully');
    }

    /**
     * Remove the specified PackageGroup from storage.
     * DELETE /packageGroups/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var PackageGroup $packageGroup */
        $packageGroup = $this->packageGroupRepository->findWithoutFail($id);

        if (empty($packageGroup)) {
            return $this->sendError('Package Group not found');
        }

        $packageGroup->delete();

        return $this->sendResponse($id, 'Package Group deleted successfully');
    }
}
