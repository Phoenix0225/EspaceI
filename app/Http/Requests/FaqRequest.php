<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
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
            'question'      => 'Required|min:2|max:150',
            'reponse'       => 'Required|min:2',
            'faq_groupe_id' => 'Required|numeric|integer|min:1',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'question.required'         => 'Une question est requise',
            'question.min'              => 'Veuillez entrer une question de plus de 2 caractères',
            'question.max'              => "Veuillez entrer une question d'un maximum de 150 caractères",
            'reponse.required'          => 'Une réponse est requise',
            'reponse.min'               => 'Veuillez entrer une réponse de plus de 2 caractères',
            'faq_groupe_id.required'    => 'Veuillez choisir une catégorie ou en créer une nouvelle',
            'faq_groupe_id.min'         => 'Veuillez choisir une catégorie ou en créer une nouvelle',
            'faq_groupe_id.numeric'     => 'Catégorie invalide',
            'faq_groupe_id.integer'     => 'Catégorie invalide',
        ];
    }
}
