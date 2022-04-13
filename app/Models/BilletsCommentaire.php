<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BilletsCommentaire extends Model
{
    use HasFactory;

    protected $table = 'billets_commentaires';

    public function Billet()
    {
        return $this->hasOne('App\Models\Billet');
    }

    public function Utilisateur()
    {
        return $this->hasOne('App\Models\Utilisateur');
    }
}
