<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Repositories\SchoolRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Response;

/**
 * Class SchoolController
 * @package App\Http\Controllers\API
 */
class SchoolAPIController extends AppBaseController
{
    /** @var  SchoolRepository */
    private $schoolRepository;

    public function __construct(SchoolRepository $schoolRepo)
    {
        $this->schoolRepository = $schoolRepo;
    }

    /**
     * Display a listing of the School.
     * GET|HEAD /schools
     *
     * @param string $term
     * @param Request $request
     * @return Response
     * @throws RepositoryException
     */
    public function index($term = '', Request $request)
    {
        $this->schoolRepository->pushCriteria(new RequestCriteria($request));
        $this->schoolRepository->pushCriteria(new LimitOffsetCriteria($request));
        $this->schoolRepository->orderBy('name');
        $province = trim($request->get('province'));
        $this->schoolRepository->scopeQuery(function ($query) use ($term, $province) {
            $query = $query->where('active', 1);
            if ($term) {
                $term = trim(str_replace(' ', '%', $term));
                $query = $query->where('name', 'like', "$term%");
            }
            if ($province) {
                $query = $query->where('province', 'like', "%{$province}%");
            }
            return $query;
        });
        $schools = $this->schoolRepository->take(10);
        Log::debug('Escuelas a devolver', $schools->toArray());
        if($province){
            Log::debug('Se filtraron por provicina', [$province]);
        }
        return $this->sendResponse($schools->toArray());
    }
}