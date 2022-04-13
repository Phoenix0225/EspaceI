<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    use HasFactory;
    protected $table = 'inscriptions';
    public $timestamps = false;

    public function Evenement()
    {
        return $this->hasMany('App\Models\Evenement');
    }
}
