<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqGroupe extends Model
{
    use HasFactory;

    protected $fillable = [
        'groupe',
    ];

    /**
     * Une catégorie peut avoir plusieurs produits.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Faq()
    {
        return $this->hasMany('App\Models\Faq');
    }
}
