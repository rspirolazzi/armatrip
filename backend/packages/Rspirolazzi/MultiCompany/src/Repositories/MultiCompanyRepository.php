<?php

namespace Rspirolazzi\MultiCompany\Repositories;

use InfyOm\Generator\Common\BaseRepository;
use Rspirolazzi\MultiCompany\Models\Company;

/**
 * Class CategoryRepository
 * @package App\Repositories
 * @version January 4, 2019, 2:37 pm UTC
 *
 * @method Company findWithoutFail($id, $columns = ['*'])
 * @method Company find($id, $columns = ['*'])
 * @method Company first($columns = ['*'])
*/
class MultiCompanyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'code',
        'description',
        'config',
        'active'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Company::class;
    }
}

