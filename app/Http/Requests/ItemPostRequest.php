<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemPostRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        if(request()->isMethod('post')){
            return [
                'name_item' => 'required|string',
                'opening_price' => 'required|numeric',
                'image_item' => 'required|image|mimes:png,jpg,jpeg,svg|max:2048',
                'description_item' => 'required|string',
                'date_time' => 'nullable'
            ];
        }else{
            return [
                'name_item' => 'required|string',
                'image_item' => 'nullable|image|mimes:png,jpg',
                'description_item' => 'required|string',
                'date_time' => 'nullable',
                'opening_price' => 'required|numeric'
            ];
        }
    }
}
