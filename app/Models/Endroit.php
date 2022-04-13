<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endroit extends Model
{
    use HasFactory;

    protected $fillable = [
        'adresse',
        'lieu'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Disponibilite()
    {
        return $this->hasMany('App\Models\Disponibilite');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Evenement()
    {
        return $this->hasMany('App\Models\Evenement');
    }
}
