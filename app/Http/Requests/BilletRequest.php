<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BilletRequest extends FormRequest
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
            'titre'                 => 'Required|max:50',
            'nom_client'            => 'Required|max:200',
            'courriel'              => 'Required|max:250|email',
            'description_billet'    => 'Required',
            'billet_categorie_id'   => 'Required|min:1|numeric|integer',
            'telephone'             => 'max:12|Regex:/[0-9]{3}-[0-9]{3}-[0-9]{4}/',
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
            'titre.required'                => 'Une question est requise',
            'titre.max'                     => "Veuillez entrer un titre de moins de 50 caractères",
            'nom_client.required'           => 'Votre nom est requis pour ouvrir un billet',
            'nom_client.max'                => 'Veuillez entrer un nom de moins de 200 caractères',
            'courriel.required'             => 'Veuillez entrer votre adresse courriel',
            'courriel.max'                  => 'Veuillez entrer un courriel de moins de 200 caractères',
            'courriel.email'                => 'Veuillez entrer un courriel valide',
            'description_billet.required'   => 'Veuillez nous décrire votre requête',
            'billet_categorie_id.required'  => 'Veuillez choisir une catégorie',
            'billet_categorie_id.min'       => 'Veuillez choisir une catégorie',
            'billet_categorie_id.numeric'   => 'Catégorie invalide',
            'billet_categorie_id.integer'   => 'Catégorie invalide',
            'telephone.regex'               => 'Numéro de téléphone invalide',
            'telephone.max'                 => 'Numéro de téléphone invalide',
        ];
    }
}
