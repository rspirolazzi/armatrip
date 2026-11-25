<?php

namespace App\Http\Requests\API;

use Rspirolazzi\Coupons\Models\Coupon;
use InfyOm\Generator\Request\APIRequest;

class RegisterUserAPIRequest extends APIRequest
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
        return [
            'name' => 'required',
            'email' => 'required',
            'package_code' => 'required',
            'year' => 'required_unless:type,individual_tourism|integer|between:1960,2040',
            'month' => 'required_unless:type,individual_tourism|integer|between:1,12',
            'biweekly' => 'required_unless:type,individual_tourism|integer|between:1,2',
            'date_range_id' => 'required_if:type,==,individual_tourism|integer',
            'qty' => 'required_unless:type,individual_tourism|integer|between:1,200',
            'province_code' => 'required_unless:type,individual_tourism',
            'phone_code' => 'required',
            'phone_number' => 'required',
            'store_code' => 'required',
        ];
    }
}
