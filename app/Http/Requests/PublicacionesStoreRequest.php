<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicacionesStoreRequest extends FormRequest
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
                'titulo' => 'required|string|max:258',
                'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'contenido' => 'required|string'
            ];
        }else {
            return [
                'titulo' => 'required|string|max:258',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'contenido' => 'required|string'
            ];
        }
    }

    public function messages()
    {
        if(request()->isMethod('post')){
            return [
                'titulo.required' => 'Titulo is required',
                'imagen.required' => 'Imagen is required',
                'contenido.required' => 'Contenido is required'
            ];
        }else{
            return [
                'titulo.required' => 'Titulo is required',
                'contenido.required' => 'Contenido is required'
            ];
        }
    }
}
