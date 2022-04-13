<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParametresRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\BilletCategorie;
use App\Models\Parametre;
use App\Models\Probleme;
use App\Models\Endroit;
use App\Models\TypesEvenement;
use Illuminate\Http\Request;

class ParametreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parametres = Parametre::find(1);
        $billetCategories = BilletCategorie::all();
        $problemes = Probleme::all();
        $endroits = Endroit::all();
        $types_evenements = TypesEvenement::all();

        return view('parametres.index', compact('parametres', 'billetCategories', 'problemes', 'endroits', 'types_evenements'));
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
     * @param  \App\Models\Parametre  $parametre
     * @return \Illuminate\Http\Response
     */
    public function show(Parametre $parametre)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Parametre  $parametre
     * @return \Illuminate\Http\Response
     */
    public function edit(Parametre $parametre)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Parametre  $parametre
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ParametresRequest $request)
    {
        $validated = $request->validated();

        $parametre = Parametre::find($request->route('id'));

        $parametre->duree_plage_horaire   = $validated['duree_plage_horaire'];
        $parametre->duree_rdv_max         = $validated['duree_rdv_max'];
        $parametre->rdv_heure_debut       = $validated['rdv_heure_debut'];
        $parametre->rdv_heure_fin         = $validated['rdv_heure_fin'];
        $parametre->nb_evenements_accueil = $validated['nb_evenements_accueil'];
        $parametre->nb_dispo_dashboard    = $request->nb_dispo_dashboard;
        $parametre->txt_a_propos_accueil  = $request->txt_a_propos_accueil;
        $parametre->api_key               = $request->api_key;
        $parametre->channel_id            = $request->channel_id;
        $parametre->lien_channel          = $request->lien_channel;

        for ( $i = 1; $i < 100; $i++)
        {
            if($request->has('categorie'.$i))
            {
                $rules = [
                    'categorie'.$i  => 'Required|max:75',
                    ];
                $messages = [
                    'categorie'.$i.'.required'  => 'Le nom de la catégorie de billet est obligatoire.',
                    'categorie'.$i.'.max'  => 'Le nom de la catégorie doit avoir un maximum de 75 caractères.',
                    ];

                $validated = Validator::make($request->all(), $rules, $messages);

                if($validated->fails())
                {
                    $errors = $validated->messages();
                    return redirect()->route('parametres')->withErrors($errors);
                }

                $billetCategorie = new BilletCategorie();
                $billetCategorie->categorie = $request->input('categorie'.$i);

                try
                {
                    $billetCategorie->save();
                }
                catch(\Throwable $e)
                {
                    $errors = array("Erreur # ".$e->getCode()." -> ".$e->getMessage());
                    return redirect()->route('parametres')->withErrors($errors);
                }
            }
            else {
                break;
            }
        }

        for($a=1; $a<100; $a++)
        {
            if($request->has('probleme'.$a))
            {
                $rules = [
                    'probleme'.$a  => 'Required|max:75',
                    'duree'.$a  => 'Required',
                ];
                $messages = [
                    'probleme'.$a.'.required'  => 'Le type de rendez-vous est obligatoire.',
                    'probleme'.$a.'.max'  => 'Le type de rendez-vous doit avoir un maximum de 75 caractères.',
                    'duree'.$a.'.required'  => 'La durée du rendez-vous est requise.',
                ];

                $validated = Validator::make($request->all(), $rules, $messages);

                if($validated->fails())
                {
                    $errors = $validated->messages();
                    return redirect()->route('parametres')->withErrors($errors);
                }

                $probleme = new Probleme();
                $probleme->probleme = $request->input('probleme'.$a);
                $probleme->duree = $request->input('duree'.$a);

                try
                {
                    $probleme->save();
                }
                catch(\Throwable $e)
                {
                    $errors = array("Erreur # ".$e->getCode()." -> ".$e->getMessage());
                    return redirect()->route('parametres')->withErrors($errors);
                }
            }
            else
            {
                break;
            }
        }

        for($b=1; $b<100; $b++)
        {
            if($request->has('event'.$b))
            {
                $rules = [
                    'event'.$b  => 'Required|max:75',
                ];
                $messages = [
                    'event'.$b.'.required'  => 'Le type de rendez-vous est obligatoire.',
                    'event'.$b.'.max'  => 'Le type de rendez-vous doit avoir un maximum de 75 caractères.',
                ];

                $validated = Validator::make($request->all(), $rules, $messages);

                if($validated->fails())
                {
                    $errors = $validated->messages();
                    return redirect()->route('parametres')->withErrors($errors);
                }

                $event = new TypesEvenement();
                $event->type = $request->input('event'.$b);

                try
                {
                    $event->save();
                }
                catch(\Throwable $e)
                {
                    $errors = array("Erreur # ".$e->getCode()." -> ".$e->getMessage());
                    return redirect()->route('parametres')->withErrors($errors);
                }
            }
            else
            {
                break;
            }
        }

        for($c=1; $c<100; $c++)
        {
            if($request->has('adresse'.$c))
            {
                $rules = [
                    'adresse'.$c  => 'Required|max:75',
                ];
                $messages = [
                    'adresse'.$c.'.required'  => 'Le type de rendez-vous est obligatoire.',
                    'adresse'.$c.'.max'  => 'Le type de rendez-vous doit avoir un maximum de 75 caractères.',
                ];

                $validated = Validator::make($request->all(), $rules, $messages);

                if($validated->fails())
                {
                    $errors = $validated->messages();
                    return redirect()->route('parametres')->withErrors($errors);
                }

                $endroit = new Endroit();
                $endroit->adresse = $request->input('adresse'.$c);
                $endroit->lieu = $request->input('lieu'.$c);

                try{
                    $endroit->save();
                }
                catch(\Throwable $e)
                {
                    $errors = array("Erreur # ".$e->getCode()." -> ".$e->getMessage());
                    return redirect()->route('parametres')->withErrors($errors);
                }
            }
            else
            {
                break;
            }
        }

        try
        {
            $parametre->save();
        }
        catch(\Throwable $e)
        {
            $errors = array("Erreur # ".$e->getCode()." -> ".$e->getMessage());
            return redirect()->route('parametres')->withErrors($errors);
        }

        return redirect()->route('parametres')->with('status', 'Paramètres modifiés!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function probleme_destroy($id)
    {
        $probleme = Probleme::findOrFail($id);

        try
        {
            $probleme->delete();
        }
        catch(\Throwable $e)
        {
            $errors = array("Erreur # ".$e->getCode()." -> ".$e->getMessage());
            return redirect()->back()->withErrors($errors);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function type_event_destroy($id)
    {
        $type_evenement = TypesEvenement::findOrFail($id);

        try
        {
            $type_evenement->delete();
        }
        catch(\Throwable $e)
        {
            $errors = array("Erreur # ".$e->getCode()." -> ".$e->getMessage());
            return redirect()->back()->withErrors($errors);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function endroit_destroy($id)
    {
        $endroit = Endroit::findOrFail($id);

        try
        {
            $endroit->delete();
        }
        catch(\Throwable $e)
        {
            $errors = array("Erreur # ".$e->getCode()." -> ".$e->getMessage());
            return redirect()->back()->withErrors($errors);
        }
    }
}
