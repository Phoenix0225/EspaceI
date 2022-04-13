<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Parametre;

use DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $parametre = Parametre::find(1);

        // Selection des plages horaires de l'utilisateur connecté
        $disponibilites = DB::select(DB::raw(" SELECT d.id AS id_disp
                                                     , debut
                                                     , fin
                                                     , utilisateur_id
                                                     , CONCAT(e.adresse, ' ', e.lieu) AS lieu
                                                     , CONCAT(DATE_FORMAT(debut, '%Y-%m-%d'), ' de ',
                                                         DATE_FORMAT(debut,'%H:%i'), ' à ',
                                                         DATE_FORMAT(fin,'%H:%i')) AS DescHoraire
                                              FROM disponibilites d
                                              LEFT JOIN endroits e on d.endroit_id = e.id
                                              WHERE fin > NOW() and utilisateur_id = $id
                                              ORDER BY debut
                                              LIMIT $parametre->nb_dispo_dashboard
                                             "));

        // Selection des rendez-vous selon les plages horaires
        $rendez_vous = DB::select(DB::raw("SELECT  rv.id AS id_rdv
                                                 , nom_client
                                                 , courriel
                                                 , telephone
                                                 , probleme
                                                 , DATE_FORMAT(heure_debut,'%H:%i') AS heure_debut
                                                 , duree
                                                 , description_rdv
                                                 , dis.id AS id_disp
                                            FROM rendezvous rv
                                            INNER JOIN (SELECT * FROM espacei.disponibilites
                                                        WHERE utilisateur_id = $id AND fin > NOW()) dis
                                                ON rv.disponibilite_id = dis.id
                                            LEFT JOIN problemes p on rv.probleme_id = p.id
                                            ORDER BY heure_debut
                                            "));

        // Selection des billets attitres a l'utilisateur connecte
        $billets = DB::select(DB::raw(" SELECT billets.id AS idBillet
                                             , titre
                                             , nom_client
                                             , categorie
                                             , statut
                                             , priorite
                                             , billets.created_at AS DateCreation
                                        FROM billets
                                        LEFT JOIN billet_categories bc on billets.billet_categorie_id = bc.id
                                        LEFT JOIN billet_statuts bs on billets.billet_statut_id = bs.id
                                        LEFT JOIN priorites p on billets.priorite_id = p.id
                                        WHERE utilisateur_id = $id;
                                         "));

        return view('dashboard.index', compact('disponibilites', 'rendez_vous'), compact('billets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
