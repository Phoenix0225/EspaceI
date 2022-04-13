<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'endroit_id',
        'url_zoom',
        'lien_image',
        'date_heure',
        'duree',
        'types_evenement_id'
    ];

    public function TypesEvenement()
    {
        return $this->hasOne('App\Models\TypesEvenement');
    }

    public function Endroit()
    {
        return $this->hasOne('App\Models\Endroit');
    }

    public function Participant()
    {
        return $this->belongsToMany('App\Models\Participant')->withPivot('token')->withTimestamps();
    }

    public function Inscription()
    {
        return $this->hasMany('App\Models\Inscription');
    }
}
