<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarruselImagenesStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return false;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if(request()->isMethod('post')){
            return [
                'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'alt' => 'required|string|max:258'
            ];
        }else {
            return [
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'alt' => 'required|string|max:258'
            ];
        }
    }

    public function messages()
    {
        if(request()->isMethod('post')){
            return [
                'imagen.required' => 'Imagen is required',
                'alt.required' => 'Alt is required'
            ];
        }else{
            return [
                'alt.required' => 'Alt is required'
            ];
        }
    }
}