<?php

namespace App\Http\Controllers;

use App\Http\Requests\BilletRequest;
use App\Mail\BilletEnvoyer;
use App\Models\Billet;
use App\Models\BilletCategorie;
use App\Models\BilletsCommentaire;
use App\Models\Priorite;
use App\Models\Statut;
use App\Models\Utilisateur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class BilletController extends Controller
{
    /**
     * Affiche la page des billets
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $url = URL::full();
        $parsedUrl = parse_url($url);

        $categorie = 0;
        $priorite = 0;
        $statut = 0;

        if(isset($_GET["categorie"])) {
            $categorie = intval($_GET["categorie"]);
        }

        if(isset($_GET["priorite"])) {
            $priorite = intval($_GET["priorite"]);
        }

        if(isset($_GET["statut"])) {
            $statut = intval($_GET["statut"]);
        }

        $priorites = Priorite::all();
        $billetCategories = BilletCategorie::all();
        $statuts = Statut::all();

        if($categorie < 0 || $priorite < 0 || $statut < 0
            || !is_int($categorie) || !is_int($priorite) || !is_int($statut)) {
                return redirect()->route('billets')
                    ->withErrors(['error' => 'Filtres invalides']);
        }

        //Request de base
        $billetRQ = DB::table('billets')
            ->join('billet_categories', 'billets.billet_categorie_id', '=', 'billet_categories.id')
            ->join('priorites', 'billets.priorite_id', '=', 'priorites.id')
            ->join('billet_statuts', 'billets.billet_statut_id', '=', 'billet_statuts.id');

        //Filtre en appliquant 'where' s'il le faut
        if($categorie > 0 && $priorite > 0 && $statut > 0) {
            $billetsFilter =
                $billetRQ
                    ->where('billets.billet_categorie_id', '=', $categorie)
                    ->where('billets.priorite_id', '=', $priorite)
                    ->where('billets.billet_statut_id', '=', $statut);
        }
        else if($categorie > 0 && $priorite > 0){
            $billetsFilter =
                $billetRQ
                    ->where('billets.billet_categorie_id', '=', $categorie)
                    ->where('billets.priorite_id', '=', $priorite);
        }
        else if($priorite > 0 && $statut > 0) {
            $billetsFilter =
                $billetRQ
                    ->where('billets.priorite_id', '=', $priorite)
                    ->where('billets.billet_statut_id', '=', $statut);
        }
        else if($categorie > 0 && $statut > 0) {
            $billetsFilter =
                $billetRQ
                    ->where('billets.billet_categorie_id', '=', $categorie)
                    ->where('billets.billet_statut_id', '=', $statut);
        }
        else if($categorie > 0) {
            $billetsFilter =
                $billetRQ
                    ->where('billets.billet_categorie_id', '=', $categorie);
        }
        else if($priorite > 0) {
            $billetsFilter =
                $billetRQ
                    ->where('billets.priorite_id', '=', $priorite);
        }
        else if($statut > 0) {
            $billetsFilter =
                $billetRQ
                    ->where('billets.billet_statut_id', '=', $statut);
        }
        else {
            $billetsFilter = $billetRQ
                ->where('billets.billet_statut_id', '!=', 5);
        }

        //Execute la request
        $billets =
            $billetsFilter
                ->select('billets.*', 'priorites.priorite', 'billet_categories.categorie', 'billet_statuts.statut')
                ->get();

        return view('billets.index', compact('billets', 'priorites', 'billetCategories', 'statuts'));
    }

    /**
     * Affiche le formulaire de création de nouveau billet
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $billetCategories = BilletCategorie::all();

        return view('billets.create', compact('billetCategories'));
    }

    /**
     * Sauvegarde un nouveau billet
     *
     * @param BilletRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BilletRequest $request)
    {
        $validated = $request->validated();

        $billet = new Billet;

        $regexCourrielCegep = "/([a-z]+\.)+[a-z]+\.[0-9]{2,}@edu\.cegeptr\.qc\.ca/i";
        $regexCourrielCegepEns = "/([a-z]+\.[a-z]+)+@cegeptr\.qc\.ca/i";

        $billet->nom_client = $validated['nom_client'];
        $billet->courriel = $validated['courriel'];
        $billet->telephone = $validated['telephone'];
        $billet->titre = $validated['titre'];
        $billet->billet_categorie_id = $validated['billet_categorie_id'];
        $billet->billet_statut_id = 1;
        $billet->description_billet = $validated['description_billet'];
        $billet->priorite_id = 3;

        //Vérifie la validité du courriel du Cégep
        if(!preg_match($regexCourrielCegep, $billet->courriel) && !preg_match($regexCourrielCegepEns, $billet->courriel)){
            return redirect()->route('billets.create')
                ->withErrors(['error' => 'Veuillez entrer votre adresse courriel du Cégep de Trois-Rivières'])->withInput();
        }

        try
        {
            $billet->save();
        }
        catch(\Throwable $e)
        {
            $errors = array("Erreur # ".$e->getCode()." -> ".$e->getMessage());
            return redirect()->route('billets.create')->withErrors($errors);
        }


        $data = array(
            'courriel'  => $billet->courriel,
            'nom'       => $billet->nom_client,
            'titre'     => $billet->titre,
        );

        //Envoie un accusé de réception pas courriel
        Mail::To($billet->courriel)->send(new BilletEnvoyer($data));

        if($request->previousURL) {
            return redirect($request->previousURL)->with('status', 'Billet envoyé!');
        }
        else {
            return redirect()->back()->with('status', 'Billet envoyé!');
        }
    }

    /**
     * Affiche le formulaire d'affichage du billet
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $priorites = Priorite::all();
        $billetCategories = BilletCategorie::all();
        $statuts = Statut::all();
        $utilisateurs = Utilisateur::all();

        $billet = DB::table('billets')
            ->leftjoin('billet_categories', 'billets.billet_categorie_id', '=', 'billet_categories.id')
            ->leftjoin('priorites', 'billets.priorite_id', '=', 'priorites.id')
            ->leftjoin('billet_statuts', 'billets.billet_statut_id', '=', 'billet_statuts.id')
            ->leftjoin('utilisateurs', 'billets.utilisateur_id', '=', 'utilisateurs.id')
            ->where('billets.id', '=', $id)
            ->select('billets.*', 'priorites.priorite', 'billet_categories.categorie',
                'billet_statuts.statut', 'utilisateurs.nom', 'utilisateurs.prenom')
            ->first();

        $commentaires = DB::table('billets_commentaires')
            ->leftjoin('utilisateurs', 'billets_commentaires.utilisateur_id', '=', 'utilisateurs.id')
            ->where('billets_commentaires.billet_id', '=', $id)
            ->select('billets_commentaires.commentaire', 'billets_commentaires.created_at', 'utilisateurs.nom', 'utilisateurs.prenom')
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('billets.edit', compact('billet', 'priorites', 'billetCategories', 'statuts', 'utilisateurs', 'commentaires'));
    }

    /**
     * Modifie le billet
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(BilletRequest $request, int $id)
    {
        $validated = $request->validated();

        $billet = Billet::where('id', '=', $id)->first();

        $billet->titre = $validated['titre'];
        $billet->nom_client = $validated['nom_client'];
        $billet->courriel = $validated['courriel'];
        $billet->telephone = $validated['telephone'];
        $billet->utilisateur_id = $request->utilisateur_id;
        $billet->billet_categorie_id = $validated['billet_categorie_id'];
        $billet->billet_statut_id = $request->billet_statut_id;
        $billet->description_billet = $validated['description_billet'];
        $billet->priorite_id = $request->priorite_id;

        try
        {
            $billet->save();
        }
        catch(\Throwable $e)
        {
            $errors = array("Erreur # ".$e->getCode()." -> ".$e->getMessage());
            return redirect()->back()->withInput()->withErrors($errors);
        }

        return redirect()->back()->withInput()->with('status', 'Modifications enregistrées!');
    }

    /**
     * Sauvegarde le commentaire laissé pour un billet
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCommentaire(Request $request)
    {

        $commentaire = new BilletsCommentaire();

        $commentaire->billet_id = $request->billet_id;
        $commentaire->utilisateur_id = $request->utilisateur_id;
        $commentaire->commentaire = $request->commentaire;

        try
        {
            $commentaire->save();
        }
        catch(\Throwable $e)
        {
            $errors = array("Erreur # ".$e->getCode()." -> ".$e->getMessage());
            return redirect()->back()->withInput()->withErrors($errors);
        }

        $utilisateur = Utilisateur::find($request->utilisateur_id);

        $message = json_encode(array(
            'message' => 'Commentaire envoyé!',
            'name' => $utilisateur->prenom . ' ' . $utilisateur->nom,
        ));

        return response($message);
    }

    /**
     * Supprime une catégorie de billet
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyBilletCategorie($id)
    {
        //On ne peut pas supprimer la catégorie `À définir`
        if($id != 1) {
            $billetCategorie = BilletCategorie::find($id);
            //Change les catégories des billets à `À définir`
            foreach ($billetCategorie->billets as $billet) {
                $billet->billet_categorie_id = 1;
                $billet->save();
            }

            try
            {
                $billetCategorie->delete();
            }
            catch(\Throwable $e)
            {
                $errors = array("Erreur # ".$e->getCode()." -> ".$e->getMessage());
                return redirect()->back()->withErrors($errors);
            }



            return redirect()->back()->with('status', 'Type de billet supprimé!');
        }
        else
            return redirect()->back()->withErrors(['Cette catégorie ne peut pas être supprimées.']);
    }
}
