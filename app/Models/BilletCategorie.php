<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BilletCategorie extends Model
{
    use HasFactory;

    /**
     * Une catégorie peut avoir plusieurs produits.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Billets()
    {
        return $this->hasMany('App\Models\Billet');
    }
}
