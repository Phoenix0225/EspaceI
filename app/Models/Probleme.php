<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Probleme extends Model
{
    use HasFactory;

    protected $table = 'problemes';

    protected $fillable = [
        'probleme',
        'duree',
    ];

    public function Endroit()
    {
        return $this->hasOne(Endroit::class,'id','endroit_id');
    }

    public function CategoriesProbleme()
    {
        return $this->hasOne('App\Model\CategoriesProbleme');
    }
}
