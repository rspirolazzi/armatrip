<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Invoice;
use App\Models\Admin\MediaInvoice;
use Auth;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class InternalOrderRepository
 * @package App\Repositories\Admin
 * @version February 13, 2019, 6:28 am -03
 *
 * @method Invoice findWithoutFail($id, $columns = ['*'])
 * @method Invoice find($id, $columns = ['*'])
 * @method Invoice first($columns = ['*'])
 */
class MediaInvoiceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [

    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MediaInvoice::class;
    }

    public function findOrCreateCart()
    {
        $modelName = $this->model();
        $user = Auth::user();
        $model = $this->findWhere([
            'user_id' => $user->id,
            'status' => $modelName::$STATUS_DRAFT,
            'model_type' => $modelName,
        ])->first();
        if(!$model){
            $model = $this->create([
                'number' => $modelName::generateOrderNumber($user->store_id),
                'store_id' => $user->store_id,
                'user_id' => $user->id,
                'status' => $modelName::$STATUS_DRAFT,
                'model_type' => $modelName,
            ]);
        }
        return $model;
    }
}
