<?php

namespace Hotrush\QuickBooksManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthCallbackRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required',
            'realmid' => 'required', // ?!?!?!
        ];
    }
}