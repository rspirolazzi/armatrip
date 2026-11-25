<?php

namespace Rspirolazzi\MultiCompany\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rspirolazzi\MultiCompany\Contracts\Company as CompanyContract;
/**
 * Class Company
 * @package Rspirolazzi\MultiCompany\Models
 * @version March 3, 2019, 6:27 pm -03
 *
 * @property string name
 * @property string code
 * @property string description
 * @property string config
 * @property boolean active
 */
class Company extends Model implements CompanyContract
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * Company constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('multicompany.table.companies', 'companies');
    }

    protected $fillable = [
        'name',
        'code',
        'description',
        'config',
        'active'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'code' => 'required',
        'config' => 'active boolean checkbox',
        'active' => 'required'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'code' => 'string',
        'description' => 'string',
        'active' => 'boolean'
    ];

}
