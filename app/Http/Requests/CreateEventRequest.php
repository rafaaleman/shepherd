<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEventRequest extends FormRequest
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
            'name' => 'required|min:2',
            'location' => 'required|min:2',
            'date' => 'required|min:8',
            'time' => 'required|min:5',
            'assigned' => 'required',
            'status' => 'required|numeric|max:1',
        ];
    }
}
