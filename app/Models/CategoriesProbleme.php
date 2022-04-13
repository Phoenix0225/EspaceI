<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesProbleme extends Model
{
    use HasFactory;

    protected $table = 'categories_problemes';

    public function Probleme()
    {
        return $this->hasMany('App\Models\Probleme');
    }
}
