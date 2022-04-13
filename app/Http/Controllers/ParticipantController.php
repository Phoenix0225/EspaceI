<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Mail\AnnulerInscription;
use App\Mail\ConfirmationParticipant;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Evenement;
use App\Models\Inscription;
use App\Models\Endroit;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use DB;
use Carbon\Carbon;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $evenements = Evenement::findOrFail($id);
        $participants = Participant::join('evenement_participant', 'id', '=', 'participant_id')->select('nom', 'prenom', 'courriel', 'telephone', 'evenement_id', 'token')
            ->where('evenement_id', $id)
            ->get();
        return view('event.liste_participant', compact('participants', 'evenements'));
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
        $rules = [
            'nom'                 => 'Required|min:1|max:30',
            'prenom'              => 'Required|min:1|max:30',
            'telephone'           => 'nullable|max:12|Regex:/[0-9]{3}-[0-9]{3}-[0-9]{4}/',
            'courriel'            => 'required',

        ];

        $messsages = [
            'nom.required'                  => 'Veuillez entrer votre nom.',
            'prenom.required'               => 'Veuillez entrer votre prenom.',
            'courriel.required'             => 'Veuillez entrer votre adresse courriel.',
            'nom.min'                       => "Veuillez entrer un nom d'un minimum d'un caractères",
            'nom.max'                       => 'Veuillez entrer un nom qui ne dépasse pas 30 caractères',
            'prenom.min'                    => "Veuillez entrer un prenom d'un minimum d'un caractères",
            'prenom.max'                    => 'Veuillez entrer un prenom qui ne dépasse pas 30 caractères',
            'telephone.regex'               => 'Numéro de téléphone invalide',
            'telephone.max'                 => 'Numéro de téléphone invalide',
        ];




        $regexCourrielCegep = "/([a-z]+\.)+[a-z]+\.[0-9]{2,}@edu\.cegeptr\.qc\.ca/i";
        $regexCourrielCegepEns = "/([a-z]+\.[a-z]+)+@cegeptr\.qc\.ca/i";

        $participant = new Participant;

        try {



            $participant->nom           = $request->nom;
            $participant->prenom        = $request->prenom;
            $participant->courriel      = $request->courriel;
            $participant->telephone     = $request->telephone;



            $validator = Validator::make($request->all(), $rules, $messsages);

            if ($validator->fails()) {
                $errors = $validator->messages();
                return redirect()->route('evenement')->withErrors($errors);
            }

            if (!preg_match($regexCourrielCegep, $participant->courriel) && !preg_match($regexCourrielCegepEns, $participant->courriel)) {
                return redirect()->route('evenement')
                    ->withErrors(['error' => 'Veuillez entrer votre adresse courriel du Cégep de Trois-Rivières'])->withInput();
            }

            $evenement = Evenement::findOrFail($request->id);

            $inscription = Inscription::findOrFail($request->id);
            if ($inscription->place_restante == 0) {
                return back()->withErrors("Désolé, il n'y a plus de place pour cet évènement.");
            }

            if (($evenement->date_heure < Carbon::now())) {
                return back()->withErrors("Désolé, la période d'inscription est terminé.");
            }


            if (!Participant::where('courriel', $request->courriel)->exists()) {
                $participant->save();
                $participant->id;
            } else {
                Participant::where('courriel', $request->courriel)->update(['nom' => $request->nom, 'prenom' => $request->prenom, 'telephone' => $request->telephone]);
                $participant->id = Participant::where('courriel', $request->courriel)->value('id');
            }


            if ($evenement->Participant()->get()->contains($participant->id)) {
                return back()->withErrors("Vous êtes déjà inscrit à l'évènement.");
            } else {
                $participant->Evenement()->attach($request->id);
            }


            $token = DB::table('evenement_participant')
                ->select('token')
                ->where('participant_id', $participant->id)
                ->where('evenement_id', $request->id)
                ->first();

            $endroit = DB::table('evenements')
                ->Join('endroits', 'evenements.endroit_id', '=', 'endroits.id')
                ->select('endroits.adresse', 'endroits.lieu')
                ->where('evenements.id', $request->id)
                ->first();



            $mois   = Carbon::parse($evenement->date_heure)->locale('fr')->monthName;
            $jours  = Carbon::parse($evenement->date_heure)->locale('fr')->format('j');
            $annee  = Carbon::parse($evenement->date_heure)->locale('fr')->year;
            $heure  = Carbon::parse($evenement->date_heure)->locale('fr')->format('H:i');


            $data = array(
                'courriel'  => $request->courriel,
                'nom'       => $request->nom,
                'prenom'    => $request->prenom,
                'adresse'   => $endroit->adresse,
                'lieu'      => $endroit->lieu,
                'titre'     => $evenement->titre,
                'lien'      => $evenement->url_zoom,
                'mois'      => $mois,
                'jours'     => $jours,
                'annee'     => $annee,
                'heure'     => $heure,
                'token'     => $token->token,
            );

            Mail::To($data['courriel'])->send(new ConfirmationParticipant($data));
            return redirect()->route('evenement')->with('status', 'Merci pour votre inscription! Veuillez confirmer votre inscription, vous avez 24H.');
        } catch (\Throwable $e) {
            $errors = array("Erreur # " . $e->getCode() . " -> " . $e->getMessage());
            return redirect()->route('evenement')->withErrors($errors);
        }
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
        try {

            $evenement = Evenement::join('evenement_participant', 'id', '=', 'evenement_id')->select('date_heure')
                ->where('token', $id)
                ->first();



            if (DB::table('evenement_participant')->where('token', $id)->exists()) {

                if ($evenement->date_heure < Carbon::now()) {
                    return back()->withErrors("L'évènement est terminé. Vous ne pouvez pas annuler votre inscription.");
                }

                DB::table('evenement_participant')
                    ->where('token', $id)
                    ->delete();
                return redirect()->route('evenement')->with('status', 'Inscription annulée.');
            } else {
                return back()->withErrors("Votre inscription est déjà annulé. Merci");
            }
        } catch (\Throwable $e) {
            $errors = array("Erreur # " . $e->getCode() . " -> " . $e->getMessage());
            return redirect()->route('evenement')->withErrors($errors);
        }
    }


    /**
     * Confirm the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function confirm($id)
    {
        try {

            if (DB::table('evenement_participant')->where('token', $id)->where('isPresent', 1)->exists()) {

                return redirect()->route('evenement')->with('status', 'Votre inscription est bien confirmé!');
            } else if (DB::table('evenement_participant')->where('token', $id)->exists()) {

                DB::table('evenement_participant')
                    ->where('token', $id)
                    ->update(['isPresent' => 1]);

                $idPivot = DB::table('evenement_participant')->select('participant_id', 'evenement_id')->where('token', $id)
                    ->first();

                $infoParticipant = Participant::select('nom', 'prenom', 'courriel')->where('id', $idPivot->participant_id)
                    ->first();

                $evenement = Evenement::select('titre', 'endroit_id', 'url_zoom', 'date_heure')->where('id', $idPivot->evenement_id)
                    ->first();

                $endroit = Endroit::select('adresse', 'lieu')->where('id', $evenement->endroit_id)
                    ->first();

                $mois   = Carbon::parse($evenement->date_heure)->locale('fr')->monthName;
                $jours  = Carbon::parse($evenement->date_heure)->locale('fr')->format('j');
                $annee  = Carbon::parse($evenement->date_heure)->locale('fr')->year;
                $heure  = Carbon::parse($evenement->date_heure)->locale('fr')->format('H:i');


                $data = array(
                    'courriel'  => $infoParticipant->courriel,
                    'nom'       => $infoParticipant->nom,
                    'prenom'    => $infoParticipant->prenom,
                    'adresse'   => $endroit->adresse,
                    'lieu'      => $endroit->lieu,
                    'titre'     => $evenement->titre,
                    'lien'      => $evenement->url_zoom,
                    'mois'      => $mois,
                    'jours'     => $jours,
                    'annee'     => $annee,
                    'heure'     => $heure,
                    'token'     => $id,
                );

                Mail::To($data['courriel'])->send(new AnnulerInscription($data));
                return redirect()->route('evenement')->with('status', 'Votre inscription est maintenant bien confirmé!');
            } else {
                return back()->withErrors("Désolé, vous avez dépasser le délais de 24h pour confirmer votre inscription. Veuillez complèter une nouvelle demande d'inscription. Merci");
            }
        } catch (\Throwable $e) {
            $errors = array("Erreur # " . $e->getCode() . " -> " . $e->getMessage());
            return redirect()->route('evenement')->withErrors($errors);
        }
    }

    public function delete($id)
    {
        try {

            if (DB::table('evenement_participant')->where('token', $id)->exists()) {
                $evenement =  DB::table('evenement_participant')->select('evenement_id')
                    ->where('token', $id)
                    ->first();

                DB::table('evenement_participant')
                    ->where('token', $id)
                    ->delete();
                return redirect()->route('participant.index', [$evenement->evenement_id])->with('status', 'Inscription annulée.');
            } else {
                return back()->withErrors("Le participant est déjà supprimé de cet évènement.");
            }
        } catch (\Throwable $e) {
            $errors = array("Erreur # " . $e->getCode() . " -> " . $e->getMessage());
            return redirect()->route('evenement')->withErrors($errors);
        }
    }
}
