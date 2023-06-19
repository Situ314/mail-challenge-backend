<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'subject' => 'required|string|max:55',
            'body' => 'required|string|max:255',
            'recipient' => 'required|array',
            'recipient.*' => 'required|email|string',
            'cc.*' => 'email|string',
            'bcc.*' => 'email|string',
        ];
    }
}
