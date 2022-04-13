<?php

namespace App\Http\Controllers;

use App\Http\Requests\RendezVousRequest;
use App\Models\Disponibilite;
use App\Models\Probleme;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\ConfirmationRDV;
use App\Mail\RecapitulatifRDV;
use App\Models\Parametre;
use Faker\Provider\Uuid;
use Faker\Factory;
use Nette\IOException;

class RendezVousController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rendezvous = RendezVous::all();
        return view ('appointment.index',compact('rendezvous'));
    }

    /**
     * Montrer le formulaire
     *
     * @return \Illuminate\Http\Response
     */
    public function selectCategory()
    {
        $problemes = Probleme::all();
        return view('appointment.add.category',compact('problemes'));
    }

    public function selectDate($id){
        try{
            $probleme = Probleme::findOrFail($id);
            $dispos = Disponibilite::all();
            $dates = array();
            foreach($dispos as $dispo)
                array_push($dates,date('Y-m-d',strtotime($dispo->debut)));

            return view('appointment.add.date',compact('probleme','dates'));
        }catch(\Throwable $e){
            return redirect()->route('acceuil')->withError(array('Une erreur s\'est produite lors du chargement de la catégorie. Erreur #'.$e->getCode()));
        }
    }

    public function create(Request $request)
    {
        try{
            $parametre = Parametre::all()->first();
            $date = $request->get("time");
            $dateTab = explode("-",$date);
            $probleme = Probleme::findOrFail($request->get('category'));
            $month = $dateTab[1]+1;
            $month = ($month<10?'0':'').$month;
            $debut = $dateTab[0].'-'.$month.'-'.$dateTab[2].' 00:00:00';
            $fin = $dateTab[0].'-'.$month.'-'.$dateTab[2].' 23:59:59';
            $dispos = Disponibilite::where('debut','<',$fin)->where('fin','>',$debut)->get();
            $dateList = array();
            foreach(Disponibilite::all() as $dispo)
                array_push($dateList,date('Y-m-d',strtotime($dispo->debut)));
            if(count($dispos) > 0)
                return view('appointment.add.add',compact('probleme','date','dispos','dateList','parametre'));
            else
                return redirect()->route('appointment.date',$probleme->id)->withErrors(array('Aucune disponibilité n’a été trouvée pour cette date.'));
        }catch(\Throwable $e){
            Log::debug($e);
            //return redirect()->route('appointment.category')->withErrors(array('Une erreur s\'est produite lors du chargement des disponibilités. Erreur #'.$e->getMessage()));
        }
    }


    public function store(RendezVousRequest $request)
    {
        try{
            $validation = $request->validated();

            $rdv = new RendezVous($validation);
            $dispo = Disponibilite::findOrFail($validation['disponibilite_id']);
            $probleme = Probleme::findOrFail($validation['probleme_id']);
            $rdv['duree_avg'] = $probleme->duree;

            //Vérifier si la date et l'heure du rdv est valide

            $dispoStart = strtotime($dispo->debut);
            $dispoEnd = strtotime($dispo->fin);

            $rdvStart = strtotime(date('Y-m-d',$dispoStart).' '.$validation['heure_debut']);
            $rdvEnd = strtotime('+'.$probleme->duree.' minutes',$rdvStart);
           
            $isValid = ($rdvStart >= $dispoStart && $rdvStart <= $dispoEnd) && ($rdvEnd >= $dispoStart && $rdvEnd <= $dispoEnd);
            if($isValid)
                foreach($dispo->RendezVous as $rdv2){
                    echo date('Y-m-d H:i',$rdvStart).' '.date('Y-m-d H:i',$rdvEnd);
                    $rdvStart2 = strtotime(date('Y-m-d',$dispoStart).' '.$rdv2->heure_debut);
                    $rdvEnd2 = strtotime('+'.$rdv2->duree_avg.' minutes',$rdvStart2);
                    
                    $isValid = !(($rdvStart2 >= $rdvStart && $rdvStart2 < $rdvEnd) || ($rdvEnd2> $rdvStart && $rdvEnd2 <= $rdvEnd));
                    if(!$isValid)
                        break;
                }
            $mois = array('Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre');
            
            if($isValid){
                $rdv->save();
                $token = DB::table('rendezvous')
                    ->select('token')
                    ->where('id', $rdv->id)
                    ->first();
                $data = array(
                    'courriel'  => $rdv->courriel,
                    'debut'       => $validation['heure_debut'],
                    'fin'   => date('H:i',$rdvEnd),
                    'date'  => date('j',$rdvStart).' '.$mois[date('m',$rdvStart)-1].' '.date('Y',$rdvStart),
                    'token'  => $token->token,
                );

                Mail::To($data['courriel'])->send(new ConfirmationRDV($data));
                return redirect()->route('acceuil')->with('status','La prise de rendez-vous a été effectué.');
            }else
            return redirect()->route('appointment.category')->withErrors(array('La date et l\'heure du rendez-vous n\'est pas valide.'));
            
        }catch(\Throwable $e){
            return redirect()->route('appointment.category')->withErrors(array('Une erreur s\'est produite lors de la création du rendez vous. Erreur #'.$e->getCode()));
        }
    }

    public function confirm($token){
        $rdvList = RendezVous::where('token','=',$token)->where('is_confirme','=','0')->get();
        if(!$rdvList->isEmpty()){
            $rdv = $rdvList->first();
            $rdv->is_confirme = true;
            $rdv->save();
            $data = array(
                'courriel' => $rdv->courriel,
                'date'  => $rdv->heure_debut.' '.date('j-n-Y',strtotime($rdv->Disponibilite->debut)),
                'lieu'  => $rdv->Disponibilite->Endroit->lieu.' '.$rdv->Disponibilite->Endroit->adresse,
                'type'  => $rdv->Probleme->probleme,
                'token' => $token,
            );

            Mail::To($data['courriel'])->send(new RecapitulatifRDV($data));
            return redirect()->route('acceuil')->with('status','Le rendez-vous a été confirmé');
        }else{
            return redirect()->route('acceuil')->withErrors(array('Le lien de confirmation n\'est pas valide.'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RendezVous  $rendezVous
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $rdv = RendezVous::findOrFail($id);
            return view ('appointment.show',compact('rdv'));
        }catch(IOException $e){
            return redirect()->route('appointment.index')->withErrors(array('Une erreur s\'est produite lors du chargement du rendez-vous. Erreur #'.$e->getCode()));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RendezVous  $rendezVous
     * @return \Illuminate\Http\Response
     */
    public function edit(RendezVous $rendezVous)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RendezVous  $rendezVous
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RendezVous $rendezVous)
    {
        //
    }

    public function cancel($token)
    {
        $rdvList = RendezVous::where('token','=',$token)->get();
        if(!$rdvList->isEmpty()){
            $rdvList->first()->delete();
            return redirect()->route('acceuil')->with('status','Le rendez-vous a été annulé');
        }else{
            return redirect()->route('acceuil')->withErrors(array('Le rendez-vous n\'existe pas.'));
        }
    }

    public function destroy($id)
    {
        try{
            $rdv = RendezVous::findOrFail($id);
            $rdv->delete();
            return redirect()->route('appointment.index')->with('status','Le rendez-vous a été supprimé');
        }catch(IOException $e){
            return redirect()->route('appointment.index')->withErrors(array('Une erreur s\'est produite lors de la suppression du rendez-vous. Erreur #'.$e->getCode()));
        }
    }
}
