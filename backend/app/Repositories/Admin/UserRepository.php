<?php

namespace App\Repositories\Admin;

use App\User as User;
use InfyOm\Generator\Common\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class UserRepository
 * @package App\Repositories\Admin
 * @version March 4, 2018, 10:46 pm UTC
 *
 * @method User findWithoutFail($id, $columns = ['*'])
 * @method User find($id, $columns = ['*'])
 * @method User first($columns = ['*'])
*/
class UserRepository extends BaseRepository
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
        return User::class;
    }
    /**
     * Retrieve all data of repository, paginated
     *
     * @param null|int $limit
     * @param array $columns
     * @param string $method
     *
     * @return mixed
     */
    public function paginate($limit = null, $columns = ['*'], $method = "paginate")
    {
        $this->applyCriteria();
        $this->applyScope();
        $limit = is_null($limit) ? config('repository.pagination.limit', 15) : $limit;

        //$results = $this->model->{$method}($limit, $columns);

        $results = $this->model->select('*')->whereNotExists(function($query)
                {
                    $query->select(DB::raw('*'))
                          ->from('roles')
                          ->join('model_has_roles', 'roles.id', 'model_has_roles.role_id')
                          ->whereRaw('users.id = model_has_roles.model_id');
                })
                //->toSQL();
                ->paginate($limit)
                ;

        //dd($results);

        $results->appends(app('request')->query());
        $this->resetModel();

        return $this->parserResult($results);
    }
}
