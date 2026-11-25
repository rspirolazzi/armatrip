<?php

namespace App\Repositories;

use App\Models\Admin\School;
use Illuminate\Database\Eloquent\Builder;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class SchoolRepository
 * @package App\Repositories
 * @version February 24, 2019, 10:09 am -03
 *
 * @method School findWithoutFail($id, $columns = ['*'])
 * @method School find($id, $columns = ['*'])
 * @method School first($columns = ['*'])
*/
class SchoolRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'year',
        'division',
        'turn',
        'address'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return School::class;
    }
    /**
     * Retrieve all data of repository
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function take($limit=25,$columns = ['*'])
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
