<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UtilisateurRequest extends FormRequest
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
            'nom'                 =>'Required|min:2|max:100',
            'prenom'              =>'Required|min:2|max:100',
            'courriel'            =>'Required|email|min:2|max:200',
            'telephone'           =>'max:15',
            'types_utilisateur_id'=>'Required|numeric|integer',
//          'is_admin'            =>'boolean',
            'password'            =>'Required|min:8|max:15'
        ];
    }

    public function messages()
    {
        return [
            'courriel.required' => 'Le courriel est obligatoire.',
            'courriel.email'    => 'Le courriel n\'est pas valide.',
            'courriel.min'      => 'Le courriel doit contenir un minimum de 2 caractères.',
            'courriel.max'      => 'Le courriel doit contenir un maximum de 200 caractères.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min'      => 'Le mot de passe doit contenir un minimum de 8 caractères.',
            'password.max'      => 'Le mot de passe doit contenir un maximum de 15 caractères.',
            'nom.min'           => 'Le nom de l\'utilisateur doit contenir un minimum de 2 caractères.',
            'nom.max'           => 'Le nom de l\'utilisateur doit contenir un maximum de 100 caractères.',
            'nom.required'      => 'Le nom de l\'utilisateur est obligatoire.',
            'prenom.min'        => 'Le prénom de l\'utilisateur doit contenir un minimum de 2 caractères.',
            'prenom.max'        => 'Le prénom de l\'utilisateur doit contenir un maximum de 100 caractères.',
            'prenom.required'   =>'Le prénom de l\'utilisateur est obligatoire.',
            'telephone.max'     => 'Le numéro de téléphone doit contenir un maximum de 15 caractères.',
            'types_utilisateur_id.required' => 'Vous devez sélectionner un type d\'utilisateur',
            'types_utilisateur_id.integer'  => 'Le type d\'utilisateur sélectionné n\'est pas valide',
            'types_utilisateur_id.numeric'  => 'Le type d\'utilisateur sélectionné n\'est pas valide',
        ];
    }
}
