<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccueilCommentaire extends Model
{
    use HasFactory;

    protected $table = 'accueil_commentaires';

    protected $fillable = [
        'nom_complet',
        'commentaire',
        'photo',
        'isActive',
    ];
}
