<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypesEvenement extends Model
{
    use HasFactory;

    public function Evenement()
    {
        return $this->hasMany('App\Models\Evenement');
    }
}
