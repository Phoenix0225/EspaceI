<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RendezVous extends Model
{
    use HasFactory;

    protected $table = 'rendezvous';

    protected $fillable = [
        'disponibilite_id',
        'heure_debut',
        'probleme_id',
        'nom_client',
        'courriel',
        'telephone',
        'description_rdv'
    ];

    public function Disponibilite()
    {
        return $this->hasOne(Disponibilite::class,'id','disponibilite_id');
    }

    public function BilletRendezVous()
    {
        return $this->hasMany('App\Models\BilletRendezVous');
    }

    public function Probleme(){
        return $this->hasOne(Probleme::class,'id','probleme_id');
    }
}
