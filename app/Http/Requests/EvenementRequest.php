<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvenementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'titre'=>'Required|min:2|max:100',
            'description'=>'min:5',
            'endroit_id'=>'Required',
            'url_zoom'=>'min:5|max:200',
            'lien_image'=>'min:5|max:200'
        ];
    }
}
