<?php

namespace App\Repositories\API;

use App\Models\Admin\Contact;
use App\Models\Order;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ContactRepository
 * @package App\Repositories\Admin
 * @version March 14, 2018, 6:02 pm UTC
 *
 * @method Contact findWithoutFail($id, $columns = ['*'])
 * @method Contact find($id, $columns = ['*'])
 * @method Contact first($columns = ['*'])
*/
class CheckoutRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'email',
        'comment'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Order::class;
    }
    public function create(array $attributes)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $model = parent::create($attributes);
        $this->skipPresenter($temporarySkipPresenter);

        $model = $this->updateRelations($model, $attributes);
        $model->save();

        return $this->parserResult($model);
    }
}
