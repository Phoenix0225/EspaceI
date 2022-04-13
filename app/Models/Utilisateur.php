<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Utilisateur extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'courriel',
        'telephone',
        'types_utilisateur_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function TypesUtilisateur()
    {
        return $this->hasOne('App\Models\TypesUtilisateur');
    }

    public function Disponibilite()
    {
        return $this->hasMany('App\Models\Disponibilite');
    }
}
