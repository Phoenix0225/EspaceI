<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'courriel',
        'telephone',
    ];

    public function Evenement()
    {
        return $this->belongsToMany('App\Models\Evenement')->withPivot('token')->withTimestamps();
    }

}
