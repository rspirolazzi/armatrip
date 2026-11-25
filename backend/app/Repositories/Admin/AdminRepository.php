<?php

namespace App\Repositories\Admin;

use App\Admin;
use App\Admin as AdminModel;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class UserRepository
 * @package App\Repositories\Admin
 * @version March 4, 2018, 10:46 pm UTC
 *
 * @method User findWithoutFail($id, $columns = ['*'])
 * @method User find($id, $columns = ['*'])
 * @method User first($columns = ['*'])
 */
class AdminRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'=>'like',
        'email'=>'like',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Admin::class;
    }

    /**
     * Retrieve all data of repository, paginated
     *
     * @param null $limit
     * @param array $columns
     * @param string $method
     *
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function paginate($limit = null, $columns = ['*'], $method = "paginate")
    {
        $this->applyCriteria();
        $this->applyScope();
        $limit = is_null($limit) ? config('repository.pagination.limit', 15) : $limit;
        $results = $this->model->has('roles')->{$method}($limit, $columns);
        $results->appends(app('request')->query());
        $this->resetModel();

        return $this->parserResult($results);
    }
}
