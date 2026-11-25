<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Rspirolazzi\Coupons\Models\Coupon;

class UpdateCouponRequest extends FormRequest
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
        $id = $this->route('coupons');
        $rules = Coupon::$rules;
        $rules['code']= [
            'required',
            'alpha_num',
            'max:8',
            Rule::unique(config('coupons.table', 'coupons'))
                ->ignore($id, 'id')
                ->where(function ($query) {
                    return $query->where('enabled', 1);
                })
            ,
        ];
        return $rules;
    }
}
