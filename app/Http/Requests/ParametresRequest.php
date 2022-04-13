<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParametresRequest extends FormRequest
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
            'duree_plage_horaire'   => 'Required|min:1|numeric|integer',
            'duree_rdv_max'         => 'Required|min:1|max:300|numeric|integer|gte:duree_plage_horaire',
            'rdv_heure_debut'       => 'Required|date_format:H:i:s',
            'rdv_heure_fin'         => 'Required|date_format:H:i:s|after:rdv_heure_debut',
            'nb_evenements_accueil' => 'Required|min:1|max:6|numeric|integer',
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
            'duree_plage_horaire.required'  => 'La durée des plages horaires est requise',
            'duree_plage_horaire.min'       => "La durée des plages horaires doit être d'un minimum de 1 minute",
            'duree_plage_horaire.numeric'   => "La durée des plages horaires doit être un chiffre",
            'duree_plage_horaire.integer'   => "La durée des plages horaires doit être un chiffre entier",
            'duree_rdv_max.required'        => 'La durée maximale des rendez-vous est requise',
            'duree_rdv_max.min'             => "La durée maximale des rendez-vous doit être d'un minimum de 1 minute",
            'duree_rdv_max.numeric'         => "La durée maximale des rendez-vous doit être un chiffre",
            'duree_rdv_max.integer'         => "La durée maximale des rendez-vous doit être un chiffre entier",
            'duree_rdv_max.gte'             => "La durée maximale des rendez-vous doit plus grande ou égale à la durée des plages horaires",
            'rdv_heure_debut.required'      => "L'heure de début des rendez-vous est requise",
            'rdv_heure_debut.date_format'   => "L'heure de début des rendez-vous doit être une heure selon le format Heures:Minutes:Secondes",
            'rdv_heure_fin.required'        => "L'heure de fin des rendez-vous est requise",
            'rdv_heure_fin.date_format'     => "L'heure de fin des rendez-vous doit être une heure selon le format Heures:Minutes:Secondes",
            'rdv_heure_fin.after'           => "L'heure de fin des rendez-vous doit être après l'heure de début",
        ];
    }
}
