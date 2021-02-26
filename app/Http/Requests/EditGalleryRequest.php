<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditGalleryRequest extends FormRequest
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
            'title' => 'sometimes|min:2|max:255',
            'description' => 'sometimes|max:1000',
            'images' => 'sometimes|array',
            'images.0' => 'sometimes|required',
            'images.*.url' => 'url',
        ];
    }
}
