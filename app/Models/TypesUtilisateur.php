<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypesUtilisateur extends Model
{
    use HasFactory;

    public function Utilisateur()
    {
        return $this->hasMany('App\Models\Utilisateur');
    }
}
