<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RendezVousRequest extends FormRequest
{
    protected $redirect = '/rendez-vous/nouveau/categorie';

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
            'disponibilite_id'    =>'Required|integer',
            'heure_debut'         =>'Required',
            'probleme_id'         =>'Required|integer',
            'nom_client'          =>'Required|min:3|max:30',
            'courriel'            => 'Required|max:250|email|regex:(.*@edu\.cegeptr\.qc\.ca$)',
            'telephone'           =>'Nullable|max:15',
            'description_rdv'     =>'Nullable'
        ];
    }

    public function messages()
    {
        return [
            'courriel.regex'  => 'Le courriel doit appartenir au cégep de Trois-Rivières',
            'probleme_id.Required'=>'Vous devez sélectionner une heure pour le rendez-vous',
            'heure_debut.Required'=>'Vous devez sélectionner une heure pour le rendez-vous',
            'nom_client.Required'=>'Le nom est obligatoire.',
        ];
    }
}
