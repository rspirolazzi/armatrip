<?php

namespace App\Repositories\Admin;

use App\Models\Order;
use InfyOm\Generator\Common\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class OrderRepository
 * @package App\Repositories\Admin
 * @version March 4, 2018, 10:52 pm UTC
 *
 * @method Order findWithoutFail($id, $columns = ['*'])
 * @method Order find($id, $columns = ['*'])
 * @method Order first($columns = ['*'])
*/
class OrderRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'store_id',
        'status',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Order::class;
    }
    /**
     * Retrieve all data of repository
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function limit($limit = 25, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();

        if ($this->model instanceof Builder) {
            $results = $this->model->take($limit)->get($columns);
        } else {
            $results = $this->model->take($limit)->all($columns);
        }

        $this->resetModel();
        $this->resetScope();

        return $this->parserResult($results);
    }
}
