<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\EvenementRequest;
use App\Mail\AnnulationEvenement;
use App\Models\TypesEvenement;
use App\Models\Endroit;
use App\Models\Evenement;
use App\Models\Inscription;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use DB;
use File;
use Carbon\Carbon;

class EvenementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inscriptions = Inscription::all();
        $typeEvenements = TypesEvenement::all();
        $endroits = Endroit::all();
        $evenements = DB::table('evenements')
            ->Join('endroits', 'evenements.endroit_id', '=', 'endroits.id')
            ->join('types_evenements', 'evenements.types_evenement_id', '=', 'types_evenements.id')
            ->select('evenements.*', 'endroits.adresse', 'endroits.lieu', 'types_evenements.type')
            ->get();
        return view('event.event', compact('typeEvenements', 'evenements', 'inscriptions'), compact('endroits'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $typeEvenements = TypesEvenement::all();
        $endroits = Endroit::all();


        return view('event.create_event', compact('typeEvenements'), compact('endroits'));
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
            'titre'                 => 'Required|min:2|max:40',
            'description'           => 'Required|min:10|max:2000',
            'lien_zoom'             => 'nullable|Regex:/https?:\/\//|max:200',
            'date_heure'            => 'Required|date',
            'duree'                 => 'Required|min:1|max:4',
            'nb_places'             => 'nullable|max:4',
            'lien_img'              => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:4096'

        ];

        $messsages = [
            'titre.required'                => 'Un titre est requis',
            'description.required'          => 'Une description est requise',
            'titre.min'                     => 'Veuillez entrer un titre de plus de 2 caractères',
            'titre.max'                     => 'Veuillez entrer un titre qui ne dépasse pas 40 caractères',
            'description.min'               => 'Veuillez entrer une description de plus de 10 caractères',
            'description.max'               => 'Veuillez entrer une description qui ne dépasse pas 2000 caractères',
            'duree.min'                     => "Veuillez entrer une durée d'au moins 1 chiffre",
            'duree.max'                     => 'Veuillez entrer une durée qui ne dépasse pas 4 chiffres',
            'titre.required'                => 'Veuillez entrer un titre',
            'description.required'          => 'Veuillez entrer une description',
            'date_heure.required'           => 'Veuillez choisir une date et un heure',
            'duree.required'                => 'Veuillez entrer une durée',
            'endroit_id.required'           => 'Veuillez sélectionner une adresse',
            'types_evenement_id.required'   => "Veuillez sélectionner un type d'évènement",
            'type_description.required'     => "Veuillez entrer un type d'évènement",
            'type_description.min'          => "Veuillez entrer un type d'évènement de plus de 2 caractères",
            'adresse.required'              => "Veuillez entrer une adresse",
            'adresse.min'                   => "Veuillez entrer une adresse de plus de 2 caractères",
            'lieu.required'                 => "Veuillez entrer un lieu",
            'lieu.min'                      => "Veuillez entrer un lieu de plus de 2 caractères",
            'nb_places.max'                 => 'Veuillez entrer un nombre de place qui ne dépasse pas 4 chiffres',
            'lien_img.mimes'                => "Format d'image invalide. Les formats autorisé sont: jpeg,png,jpg,gif,svg",
            'lien_img.max'                  => "La taille de l'image est trop grosse",
            'lien_zoom.regex'               => "Votre lien doit comporter http ou https"
        ];

        if ($request->type_description == null) {
            $nouvelEventIsEmpty = true;
            $rules['types_evenement_id'] = 'Required';
        } else {
            $rules['type_description'] = 'Required|min:2';
            $nouvelEventIsEmpty = false;
        }

        if ($request->adresse == null && $request->lieu == null) {
            $nouvelEndroitIsEmpty = true;
            $rules['endroit_id'] = 'Required';
        } else {
            $rules['adresse'] = 'Required|min:2|max:50';
            $rules['lieu'] = 'Required|min:2|max:50';
            $nouvelEndroitIsEmpty = false;
        }





        $validator = Validator::make($request->all(), $rules, $messsages);

        if ($validator->fails()) {
            $errors = $validator->messages();
            return redirect()->route('create_event')->withErrors($errors)->withInput();
        }


        if ($request->hasFile('lien_img')) {
            $lienImgIsEmpty = false;
            $uploadedFile = $request->file('lien_img');
            $imageName = iconv('UTF-8', 'ASCII//TRANSLIT', str_replace(' ', '_', substr($request->titre, 0, 10))) . '-' . uniqid() . '.' . $uploadedFile->extension();

            $request->lien_img->move(public_path('images_evenement'), $imageName);
        } else {
            $lienImgIsEmpty = true;
        }



        $evenement = new Evenement;

        try {
            $evenement->titre               = $request->titre;
            $evenement->description         = $request->description;
            $evenement->url_zoom            = $request->lien_zoom;
            $evenement->date_heure          = $request->date_heure;
            $evenement->duree               = $request->duree;
            $evenement->nb_places           = $request->nb_places;

            if ($nouvelEventIsEmpty) {
                $evenement->types_evenement_id  = $request->types_evenement_id;
            } else {
                $typeEvenements = new TypesEvenement;

                $typeEvenements->type = $request->type_description;

                $typeEvenements->save();

                $evenement->types_evenement_id = $typeEvenements->id;
            }

            if ($nouvelEndroitIsEmpty) {
                $evenement->endroit_id = $request->endroit_id;
            } else {
                $endroits = new Endroit;

                $endroits->adresse  = $request->adresse;
                $endroits->lieu     = $request->lieu;

                $endroits->save();

                $evenement->endroit_id = $endroits->id;
            }

            if (!$lienImgIsEmpty) {
                $evenement->lien_image    = $imageName;
            } else {
                $imageName = 'default.png';
                $evenement->lien_image    = $imageName;
            }



            $evenement->save();
        } catch (\Throwable $e) {
            Log::warning($e);
        }
        return redirect()->route('evenement')->with('status', 'Évènement ajouté.');
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
        $evenement = Evenement::find($id);
        $typeEvenements = TypesEvenement::all();
        $endroits = Endroit::all();

        return view('event.modif_event', compact('typeEvenements', 'evenement'), compact('endroits'));
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
            'titre'                 => 'Required|min:2|max:40',
            'description'           => 'Required|min:10|max:2000',
            'lien_zoom'             => 'nullable|Regex:/https?:\/\//|max:200',
            'date_heure'            => 'Required|date',
            'duree'                 => 'Required|min:1|max:4',
            'nb_places'             => 'nullable|max:4',
            'lien_img'              => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:4096'

        ];

        $messsages = [
            'titre.required'                => 'Un titre est requis',
            'description.required'          => 'Une description est requise',
            'titre.min'                     => 'Veuillez entrer un titre de plus de 2 caractères',
            'titre.max'                     => 'Veuillez entrer un titre qui ne dépasse pas 40 caractères',
            'description.min'               => 'Veuillez entrer une description de plus de 10 caractères',
            'description.max'               => 'Veuillez entrer une description qui ne dépasse pas 2000 caractères',
            'duree.min'                     => "Veuillez entrer une durée d'au moins 1 chiffre",
            'duree.max'                     => 'Veuillez entrer une durée qui ne dépasse pas 4 chiffres',
            'titre.required'                => 'Veuillez entrer un titre',
            'description.required'          => 'Veuillez entrer une description',
            'date_heure.required'           => 'Veuillez choisir une date et un heure',
            'duree.required'                => 'Veuillez entrer une durée',
            'endroit_id.required'           => 'Veuillez sélectionner une adresse',
            'types_evenement_id.required'   => "Veuillez sélectionner un type d'évènement",
            'type_description.required'     => "Veuillez entrer un type d'évènement",
            'type_description.min'          => "Veuillez entrer un type d'évènement de plus de 2 caractères",
            'adresse.required'              => "Veuillez entrer une adresse",
            'adresse.min'                   => "Veuillez entrer une adresse de plus de 2 caractères",
            'lieu.required'                 => "Veuillez entrer un lieu",
            'lieu.min'                      => "Veuillez entrer un lieu de plus de 2 caractères",
            'nb_places.max'                 => 'Veuillez entrer un nombre de place qui ne dépasse pas 4 chiffres',
            'lien_img.mimes'                => "Format d'image invalide. Les formats autorisé sont: jpeg,png,jpg,gif,svg",
            'lien_img.max'                  => "La taille de l'image est trop grosse",
            'lien_zoom.regex'               => "Votre lien doit comporter http ou https"
        ];

        if ($request->type_description == null) {
            $nouvelEventIsEmpty = true;
            $rules['types_evenement_id'] = 'Required';
        } else {
            $rules['type_description'] = 'Required|min:2';
            $nouvelEventIsEmpty = false;
        }

        if ($request->adresse == null) {
            $nouvelEndroitIsEmpty = true;
            $rules['endroit_id'] = 'Required';
        } else {
            $rules['adresse'] = 'Required|min:2';
            $nouvelEndroitIsEmpty = false;
        }

        if ($request->lieu == null) {
            $nouvelEndroitIsEmpty = true;
        } else {
            $rules['lieu'] = 'Required|min:2';
            $nouvelEndroitIsEmpty = false;
        }



        $validator = Validator::make($request->all(), $rules, $messsages);

        if ($validator->fails()) {
            $errors = $validator->messages();
            return redirect()->route('evenement_edit', $id)->withErrors($errors);
        }

        if ($request->hasFile('lien_img')) {
            $lienImgIsEmpty = false;
            $uploadedFile = $request->file('lien_img');
            $imageName = iconv('UTF-8', 'ASCII//TRANSLIT', str_replace(' ', '_', substr($request->titre, 0, 10))) . '-' . uniqid() . '.' . $uploadedFile->extension();

            $request->lien_img->move(public_path('images_evenement'), $imageName);
        } else {
            $lienImgIsEmpty = true;
        }

        try {
            $evenement = Evenement::find($id);

            $evenement->titre               = $request->titre;
            $evenement->description         = $request->description;
            $evenement->url_zoom            = $request->lien_zoom;
            $evenement->date_heure          = $request->date_heure;
            $evenement->duree               = $request->duree;
            $evenement->nb_places           = $request->nb_places;


            $inscription = Inscription::findOrFail($id);

            if ($evenement->nb_places == null) {
                $evenement->nb_places = null;
            } else if ($inscription->nb_incription > $evenement->nb_places) {
                return back()->withErrors("Vous ne pouvez pas réduire le nombre de place plus bas, car il y a déjà: $inscription->nb_incription inscriptions");
            }

            if ($nouvelEventIsEmpty) {
                $evenement->types_evenement_id  = $request->types_evenement_id;
            } else {
                $typeEvenements = new TypesEvenement;

                $typeEvenements->type = $request->type_description;

                $typeEvenements->save();

                $evenement->types_evenement_id = $typeEvenements->id;
            }

            if ($nouvelEndroitIsEmpty) {
                $evenement->endroit_id = $request->endroit_id;
            } else {
                $endroits = new Endroit;

                $endroits->adresse  = $request->adresse;
                $endroits->lieu     = $request->lieu;

                $endroits->save();

                $evenement->endroit_id = $endroits->id;
            }

            if (!$lienImgIsEmpty) {
                if ($evenement->lien_image != 'default.png') {
                    File::delete(public_path('images_evenement/' . $evenement->lien_image));
                }

                $evenement->lien_image    = $imageName;
            } else {
                $evenement->lien_image    = $evenement->lien_image;
            }

            $evenement->touch();
            $evenement->save();

            return redirect()->route('evenement')->with('status', 'Évènement modifié.');
        } catch (\Throwable $e) {
            $errors = array("Erreur # " . $e->getCode() . " -> " . $e->getMessage());
            return redirect()->route('evenement')->withErrors($errors);
        }
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

            $evenement = Evenement::findOrFail($id);

            if ($evenement->lien_image != 'default.png') {
                File::delete(public_path('images_evenement/' . $evenement->lien_image));
            }


                $participants = DB::table('evenement_participant')
                ->Join('participants', 'evenement_participant.participant_id', '=', 'participants.id')
                ->select('participants.nom', 'participants.prenom', 'participants.courriel')
                ->where('evenement_id', $id)
                ->get();

            $endroit = DB::table('evenements')
                ->Join('endroits', 'evenements.endroit_id', '=', 'endroits.id')
                ->select('endroits.adresse', 'endroits.lieu')
                ->where('evenements.id', $id)
                ->first();

                $mois   = Carbon::parse($evenement->date_heure)->locale('fr')->monthName;
                $jours  = Carbon::parse($evenement->date_heure)->locale('fr')->format('j');
                $annee  = Carbon::parse($evenement->date_heure)->locale('fr')->year;
                $heure  = Carbon::parse($evenement->date_heure)->locale('fr')->format('H:i');

            if($evenement->date_heure > Carbon::now()){
            foreach ($participants as $participant){

    
    
                $data = array(
                    'courriel'  => $participant->courriel,
                    'nom'       => $participant->nom,
                    'prenom'    => $participant->prenom,
                    'adresse'   => $endroit->adresse,
                    'lieu'      => $endroit->lieu,
                    'titre'     => $evenement->titre,
                    'lien'      => $evenement->url_zoom,
                    'mois'      => $mois,
                    'jours'     => $jours,
                    'annee'     => $annee,
                    'heure'     => $heure,
                );

                Mail::To($data['courriel'])->send(new AnnulationEvenement($data));



            }
        }


            DB::table('evenement_participant')
            ->where('evenement_id', $id)
            ->delete();
            Evenement::find($id)->delete();
            









            return redirect()->route('evenement')->with('status', 'Évènement supprimé.');
        } catch (\Throwable $e) {
            $errors = array("Erreur # " . $e->getCode() . " -> " . $e->getMessage());
            return redirect()->route('evenement')->withErrors($errors);
        }
    }
}
