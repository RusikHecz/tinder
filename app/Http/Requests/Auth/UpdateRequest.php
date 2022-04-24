<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateRequest extends FormRequest
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
            'name' => ['nullable','string', 'max:255', 'min:2'],
            'image' => ['nullable', 'image'],
            'email' => 'nullable','string', 'email', 'max:255', 'unique:users' ,
            'age' => ['nullable','integer'],
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'nullable|integer|exists:tags,id',
        ];
    }
    public function messages(){
        return [
            'name.required' => 'Название обязательный элемент',
            'email.required' => 'Заполните почту',
        ];
    }
}
