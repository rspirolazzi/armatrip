<?php
/**
 * Created by PhpStorm.
 * User: rodrigos
 * Date: 2019-08-23
 * Time: 2:39 p.m.
 */

namespace App\Http\Controllers\Admin;


use App\Repositories\Admin\MediaRepository;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;

class MediaController
{
    /** @var  PackageRepository */
    private $mediaRepository;

    public function __construct(MediaRepository $mediaRepo)
    {
        $this->mediaRepository = $mediaRepo;
    }

    public function index(Request $request)
    {
        $this->mediaRepository->pushCriteria(new RequestCriteria($request));
        $medias = $this->mediaRepository->paginate(25);
        return view('admin.medias.index')
            ->with('medias', $medias);
    }
}