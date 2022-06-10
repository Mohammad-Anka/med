<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotCreateRequest extends FormRequest
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
            'title' => 'required|string',
           // 'subject' => 'required',
            'useremail' => 'required|exists:users,email',
            'photo' => 'nullable|mimes:png,jpg,jpeg',
            'appointment'=>'required',
           // 'subtitle'=>'required',
    
        ];
    }
}
