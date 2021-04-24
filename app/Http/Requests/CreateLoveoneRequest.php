<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLoveoneRequest extends FormRequest
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
            'id' => 'required|numeric',
            'firstname' => 'required|min:2',
            'lastname' => 'required|min:2',
            'email' => 'email',
            'phone' => 'required|unique:loveones',
            'address' => '',
            'dob' => 'required|date',
            'relationship_id' => 'required|numeric',
            'condition_ids' => '',
            'status' => 'required|numeric|max:1',
        ];
    }
}
