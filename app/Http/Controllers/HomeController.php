<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Parametre;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     *
    public function __construct()
    {
        $this->middleware('auth');
    }*/
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $parametre = Parametre::findOrFail(1);

        $evenements = DB::select(DB::raw("SELECT v.id AS id_event
                                              , titre
                                              , lien_image
                                              , date_heure
                                              , DATE_FORMAT(date_heure, '%H:%i') AS heure
                                              , duree
                                              , nb_places
                                              , adresse
                                              , lieu
                                         FROM evenements v
                                         LEFT JOIN endroits e on v.endroit_id = e.id
                                         WHERE date_heure > NOW()
                                         ORDER BY date_heure
                                         LIMIT $parametre->nb_evenements_accueil
                                                "));

        return view('welcome', compact('evenements'), compact('parametre'));
    }
}
