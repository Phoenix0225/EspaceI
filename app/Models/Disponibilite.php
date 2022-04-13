<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disponibilite extends Model
{
    use HasFactory;

    protected $fillable = [
        'endroit_id',
        'debut',
        'fin',
        'utilisateur_id',
    ];

    public function Endroit()
    {
        return $this->hasOne(Endroit::class,'id','endroit_id');
    }

    public function Utilisateur()
    {
        return $this->hasOne('App\Models\Utilisateur');
    }

    public function RendezVous()
    {
        return $this->hasMany('App\Models\RendezVous');
    }
}
