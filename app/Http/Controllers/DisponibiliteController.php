<?php

namespace App\Http\Controllers;

use App\Mail\AnnulerRDV;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

use App\Models\Utilisateur;
use App\Models\Endroit;
use App\Models\Disponibilite;
use App\Models\Parametre;
use Carbon\Carbon;
use DB;

class DisponibiliteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $disponibilites = DB::select(DB::raw("SELECT d.id AS id
                                                   , CONCAT(/*e.adresse, ' ',*/ e.lieu) AS endroit
                                                   , CONCAT(DATE_FORMAT(d.debut, '%Y-%m-%d'),'T', DATE_FORMAT(d.debut, '%T')) AS debut
                                                   , CONCAT(DATE_FORMAT(d.fin, '%Y-%m-%d'),'T', DATE_FORMAT(d.fin, '%T')) AS fin
                                                   , CONCAT(u.prenom, ' ', u.nom) AS personne
                                               FROM disponibilites d
                                               LEFT JOIN endroits e on d.endroit_id = e.id
                                               LEFT JOIN utilisateurs u on d.utilisateur_id = u.id
                                               "));

        return view('disponibilites.index', compact('disponibilites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $utilisateurs = Utilisateur::all();
        $endroits = Endroit::all();

        return view('disponibilites.create', compact('utilisateurs'), compact('endroits'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [
            'endroit_id'       =>'Required|numeric',
            'debut'            =>'Required',
            'fin'              =>'Required',
            'utilisateur_id'   =>'Required|numeric',
        ];
        $messages = [
            'endroit_id.required'     => 'Le lieu est obligatoire.',
            'debut.required'          => 'La date de début est obligatoire.',
            'fin.required'            => 'La date de fin est obligatoire.',
            'utilisateur_id.required' => 'Vous devez sélectionner un utilisateur',
        ];

        $validated = Validator::make($request->all(), $rules, $messages);

        if($request->debut > $request->fin)
        {
            $errors = array("La fin de la disponibilité ne peut être avant le début de celle-ci.");
            return redirect()->route('disponibilites.create')->withErrors($errors)->withInput();
        }

        if(date('Y-m-d', strtotime($request->debut)) != date('Y-m-d', strtotime($request->fin)))
        {
            $errors = array("Le début et la fin de la disponibilité doivent être la même journée");
            return redirect()->route('disponibilites.create')->withErrors($errors)->withInput();
        }

        $nonDisponible = DB::select(DB::raw("SELECT *
                                            FROM disponibilites
                                            WHERE utilisateur_id = $request->utilisateur_id
                                                  AND ('$request->debut' BETWEEN DATE_ADD(debut, INTERVAL 1 SECOND ) and DATE_ADD(fin, INTERVAL -1 SECOND))
                                                  OR ('$request->fin' BETWEEN DATE_ADD(debut, INTERVAL 1 SECOND ) and DATE_ADD(fin, INTERVAL -1 SECOND))
                                                  OR (debut BETWEEN '$request->debut' AND '$request->fin')
                                             "));

        if(count($nonDisponible) > 0)
        {
            $errors = array("Cet usager est indisponible pour cette plage horaire.");
            return redirect()->route('disponibilites.create')->withErrors($errors)->withInput();
        }

        if($validated->fails())
        {
            $errors = $validated->messages();
            return redirect()->route('disponibilites.create')->withErrors($errors)->withInput();
        }

        $disponibilite = new Disponibilite;

        try
        {
            $disponibilite->endroit_id = $request->endroit_id;
            $disponibilite->debut = $request->debut;
            $disponibilite->fin = $request->fin;
            $disponibilite->utilisateur_id = $request->utilisateur_id;

            $disponibilite->save();
        }
        catch(\Throwable $e)
        {
            $errors = array("Une erreur s'est produite. Erreur -> ".$e->getMessage());
            return redirect()->route('disponibilites.create')->withErrors($errors)->withInput();
        }

        return redirect()->route('disponibilites.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_recur(Request $request)
    {
        $rules = [
            'endroit_id'       =>'Required|numeric',
            'debut'            =>'Required',
            'fin'              =>'Required',
            'utilisateur_id'   =>'Required|numeric',
            'date_max'         =>'Required',
        ];
        $messages = [
            'endroit_id.required'     => 'Le lieu est obligatoire.',
            'debut.required'          => 'La date de début est obligatoire.',
            'fin.required'            => 'La date de fin est obligatoire.',
            'utilisateur_id.required' => 'Vous devez sélectionner un utilisateur',
            'date_max.required'       => 'Vous devez sélectionner la date de fin de la récurence',
        ];

        $validated = Validator::make($request->all(), $rules, $messages);

        if($validated->fails())
        {
            $errors = $validated->messages();
            return redirect()->route('disponibilites.create')->withErrors($errors)->withInput();
        }

        if($request->debut > $request->fin)
        {
            $errors = array("La fin de la disponibilité ne peut être avant le début de celle-ci.");
            return redirect()->route('disponibilites.create')->withErrors($errors)->withInput();
        }

        if($request->date_max < $request->fin)
        {
            $errors = array("La fin de la récurensce ne peut être avant la date de fin du rendez-vous");
            return redirect()->route('disponibilites.create')->withErrors($errors)->withInput();
        }

        $nonDisponible = DB::select(DB::raw("SELECT *
                                            FROM disponibilites
                                            WHERE utilisateur_id = $request->utilisateur_id
                                                  AND ('$request->debut' BETWEEN DATE_ADD(debut, INTERVAL 1 SECOND ) and DATE_ADD(fin, INTERVAL -1 SECOND))
                                                  OR ('$request->fin' BETWEEN DATE_ADD(debut, INTERVAL 1 SECOND ) and DATE_ADD(fin, INTERVAL -1 SECOND))
                                                  OR (debut BETWEEN '$request->debut' AND '$request->fin')
                                             "));

        if(count($nonDisponible) > 0)
        {
            $errors = array("Cet usager est indisponible pour cette plage horaire.");
            return redirect()->route('disponibilites.create')->withErrors($errors)->withInput();
        }

        try
        {
            DB::select('call sp_add_disponibilites(?,?,?,?,?)',
                array($request->debut
                    , $request->fin
                    , $request->endroit_id
                    , $request->utilisateur_id
                    , $request->date_max
                ));
        }
        catch(\Throwable $e)
        {
            $errors = array("Une erreur s'est produite. Erreur -> ".$e->getMessage());
            return redirect()->route('disponibilite.create')->withErrors($errors)->withInput();
        }

        return redirect()->route('disponibilites.index');

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
        $utilisateurs = Utilisateur::all();
        $endroits = Endroit::all();

        $disponibilites = DB::select(DB::raw("SELECT id
                                                  , endroit_id
                                                  , CONCAT(DATE_FORMAT(debut, '%Y-%m-%d'),'T', DATE_FORMAT(debut, '%T')) AS debut
                                                  , CONCAT(DATE_FORMAT(fin, '%Y-%m-%d'),'T', DATE_FORMAT(fin, '%T')) AS fin
                                                  , utilisateur_id
                                             FROM disponibilites
                                             WHERE id = $id "));

        return view('disponibilites.edit', compact('disponibilites', 'utilisateurs'),compact('endroits'));
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

        $rules = [
            'endroit_id'       =>'Required|numeric',
            'debut'            =>'Required',
            'fin'              =>'Required',
            'utilisateur_id'   =>'Required|numeric',
        ];
        $messages = [
            'endroit_id.required'     => 'Le lieu est obligatoire.',
            'debut.required'          => 'La date de début est obligatoire.',
            'fin.required'            => 'La date de fin est obligatoire.',
            'utilisateur_id.required' => 'Vous devez sélectionner un utilisateur',
        ];

        $validated = Validator::make($request->all(), $rules, $messages);

        if($validated->fails())
        {
            $errors = $validated->messages();
            return redirect()->route('disponibilites.create')->withErrors($errors);
        }

        if($request->debut > $request->fin)
        {
            $errors = array("La fin de la disponibilité ne peut être avant le début de celle-ci.");
            return redirect()->route('disponibilites.edit')->withErrors($errors);
        }

        try
        {
            $disponibilite = Disponibilite::findOrFail($id);

            $disponibilite->endroit_id = $request->endroit_id;
            $disponibilite->debut = $request->debut;
            $disponibilite->fin = $request->fin;
            $disponibilite->utilisateur_id = $request->utilisateur_id;

            $disponibilite->save();
        }
        catch(\Throwable $e)
        {
            $errors = array("Erreur # ".$e->getCode()." -> ".$e->getMessage());
            return redirect()->route('disponibilites.edit')->withErrors($errors);
        }

        return redirect()->route('disponibilites.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $disponibilite = Disponibilite::findOrFail($id);

        $rendezvous = DB::table('rendezvous')
            ->where('disponibilite_id', $id)
            ->get();

        $mois   = Carbon::parse($disponibilite->debut)->locale('fr')->monthName;
        $jours  = Carbon::parse($disponibilite->debut)->locale('fr')->format('j');
        $annee  = Carbon::parse($disponibilite->debut)->locale('fr')->year;


        foreach ($rendezvous as $rdv)
        {
            $data = array
            (
                'courriel' => $rdv->courriel,
                'nom'      => $rdv->nom_client,
                'mois'     => $mois,
                'jours'    => $jours,
                'annee'    => $annee,
                'heure'    => $rdv->heure_debut,
            );

            Mail::To($data['courriel'])->send(new AnnulerRDV($data));
        }

        try
        {
            DB::table('rendezvous')
                ->where('disponibilite_id', $id)
                ->delete();

            $disponibilite->delete();
        }
        catch(\Throwable $e)
        {
            $errors = array("Erreur # ".$e->getCode()." -> ".$e->getMessage());
            return redirect()->route('disponibilites.index')->withErrors($errors);
        }

        return redirect()->route('disponibilites.index');
    }
}
