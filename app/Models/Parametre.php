<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametre extends Model
{
    protected $fillable = [
        'duree_plage_horaire',
        'duree_rdv_max',
        'rdv_heure_debut',
        'rdv_heure_fin',
    ];

    use HasFactory;
}
