<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BilletRendezVous extends Model
{
    use HasFactory;

    protected $table = 'billets_rendezvous';

    public function RendezVous()
    {
        return $this->hasMany('App\Models\RendezVous');
    }
}
