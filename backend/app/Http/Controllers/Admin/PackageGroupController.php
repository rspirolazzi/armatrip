<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Traits\AttachMedia;
use App\Http\Requests\Admin\CreatePackageGroupRequest;
use App\Http\Requests\Admin\UpdatePackageGroupRequest;
use App\Models\Admin\Package;
use App\Models\Admin\PackageGroup;
use App\Repositories\Admin\PackageGroupRepository;
use Flash;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class PackageGroupController extends AppBaseController
{
    use AttachMedia;
    /** @var  PackageGroupRepository */
    private $packageGroupRepository;

    public static $STYLE_OPTIONS = [
        'option1' => [
            'name' => 'Estilo 1',
            'color' => '#ffffff',
            'backgroundColor' => '#000000',
        ],
        'option2' => [
            'name' => 'Estilo 2',
            'color' => '#ffffff',
            'backgroundColor' => '#02d6da',
        ],
        'option3' => [
            'name' => 'Estilo 3',
            'color' => '#ffffff',
            'backgroundColor' => '#ff57b2',
        ],
        'option4' => [
            'name' => 'Estilo 4',
            'color' => '#ffffff',
            'backgroundColor' => '#48beda',
        ],
        'option5' => [
            'name' => 'Estilo 5',
            'color' => '#ffffff',
            'backgroundColor' => '#fbca6d',
        ],
        'option6' => [
            'name' => 'Estilo 6',
            'color' => '#000000',
            'backgroundColor' => '#ffffff',
        ],
    ];

    public function __construct(PackageGroupRepository $packageGroupRepo)
    {
        $this->packageGroupRepository = $packageGroupRepo;
    }

    /**
     * Display a listing of the PackageGroup.
     *
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        $this->packageGroupRepository->pushCriteria(new RequestCriteria($request));
        $packageGroups = $this->packageGroupRepository->all();

        return view('admin.package_groups.index')
            ->with('packageGroups', $packageGroups);
    }

    /**
     * Show the form for creating a new PackageGroup.
     *
     * @return Response
     */
    public function create()
    {
        $order = PackageGroup::max('order');
        $order = $order ? $order + 1 : 1;
        return view('admin.package_groups.create')
            ->with('order', $order);
    }

    /**
     * Store a newly created PackageGroup in storage.
     *
     * @param CreatePackageGroupRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreatePackageGroupRequest $request)
    {
        $input = $request->all();

        if ($input['style_value'] && isset(self::$STYLE_OPTIONS[$input['style_value']])) {
            $input['styles'] = json_encode(array_merge(['value' => $input['style_value']], self::$STYLE_OPTIONS[$input['style_value']]));
        }

        $packageGroup = $this->packageGroupRepository->create($input);

        $this->uploadImage($request, 'background_image', $packageGroup, Package::$GROUP_IMAGE);
        $this->uploadImage($request, 'background_image_mobile', $packageGroup, Package::$GROUP_MOBILE);

        Flash::success('Grupo guardadó con éxito');

        return redirect(route('admin.packageGroups.index'));
    }

    /**
     * Display the specified PackageGroup.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $packageGroup = $this->packageGroupRepository->findWithoutFail($id);

        if (empty($packageGroup)) {
            Flash::error('Grupo no encontrado');

            return redirect(route('admin.packageGroups.index'));
        }

        return view('admin.package_groups.show')->with('packageGroup', $packageGroup);
    }

    /**
     * Show the form for editing the specified PackageGroup.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $packageGroup = $this->packageGroupRepository->findWithoutFail($id);

        if (empty($packageGroup)) {
            Flash::error('Grupo no encontrado');

            return redirect(route('admin.packageGroups.index'));
        }
        return view('admin.package_groups.edit')->with('packageGroup', $packageGroup);
    }

    /**
     * Update the specified PackageGroup in storage.
     *
     * @param  int $id
     * @param UpdatePackageGroupRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id, UpdatePackageGroupRequest $request)
    {
        $packageGroup = $this->packageGroupRepository->findWithoutFail($id);

        if (empty($packageGroup)) {
            Flash::error('Grupo no encontrado');

            return redirect(route('admin.packageGroups.index'));
        }
        $input = $request->all();

        if ($input['style_value'] && isset(self::$STYLE_OPTIONS[$input['style_value']])) {
            $input['styles'] = json_encode(array_merge(['value' => $input['style_value']], self::$STYLE_OPTIONS[$input['style_value']]));
        }

        $packageGroup = $this->packageGroupRepository->update($input, $id);

        $this->uploadImage($request, 'background_image', $packageGroup, Package::$GROUP_IMAGE);
        $this->uploadImage($request, 'background_image_mobile', $packageGroup, Package::$GROUP_MOBILE);

        Flash::success('Grupo actualizadó con éxito');

        return redirect(route('admin.packageGroups.index'));
    }

    /**
     * Remove the specified PackageGroup from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $packageGroup = $this->packageGroupRepository->findWithoutFail($id);

        if (empty($packageGroup)) {
            Flash::error('Grupo no encontrado');

            return redirect(route('admin.packageGroups.index'));
        }

        $this->packageGroupRepository->delete($id);

        Flash::success('Grupo eliminadó con éxito');

        return redirect(route('admin.packageGroups.index'));
    }
}
