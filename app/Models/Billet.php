<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billet extends Model
{
    use HasFactory;

    public function BilletCategorie()
    {
        return $this->hasOne('App\Models\BilletCategorie');
    }

    public function Priorite()
    {
        return $this->hasOne('App\Models\Priorite');
    }

    public function Statut()
    {
        return $this->hasOne('App\Models\Statut');
    }

    public function Utilisateur()
    {
        return $this->hasOne('App\Models\Utilisateur');
    }

    public function BilletsCommentaire()
    {
        return $this->hasMany('App\Models\BilletsCommentaire');
    }
}
