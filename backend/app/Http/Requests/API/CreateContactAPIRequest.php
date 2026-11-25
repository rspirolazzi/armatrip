<?php

namespace App\Http\Requests\API;

use App\Models\Contact;
use App\Rules\ValidRecaptcha;
use InfyOm\Generator\Request\APIRequest;

class CreateContactAPIRequest extends APIRequest
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
        $rules = Contact::$rules + ['isVerified' => ['required', new ValidRecaptcha()]];
        if (!config('armatrip.captchaIsActive', true)) {
            unset($rules['isVerified']);
        }
        return $rules;
    }
}
