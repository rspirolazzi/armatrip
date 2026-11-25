<?php
/**
 * Created by PhpStorm.
 * User: rodrigos
 * Date: 2019-03-07
 * Time: 7:45 p.m.
 */

namespace App\Http\Requests\API;


use App\Models\Admin\Province;
use InfyOm\Generator\Request\APIRequest;

class UpdateProvinceAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Province::$rules;
    }
}