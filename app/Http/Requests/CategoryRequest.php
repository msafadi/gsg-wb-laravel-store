<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
        $id = $this->route('id');
        
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                //'unique:categories,name,' . $id,
                Rule::unique('categories', 'name')->ignore($id, 'id'),
                //(new Unique('categories', 'name'))->ignore($id)
            ],
            'parent_id' => 'nullable|int|exists:categories,id',
            'description' => 'nullable|string|min:5',
            'image' => 'nullable|image', //'|max:50|dimensions:min_width=150,min_height=150,max_width=300,max_height=300', // 50KB
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Required!',
        ];
    }
}
