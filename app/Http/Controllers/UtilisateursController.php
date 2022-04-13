<?php

namespace App\Http\Controllers;

use Hamcrest\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

use App\Http\Requests\UtilisateurRequest;
use App\Models\Utilisateur;
use App\Models\TypesUtilisateur;

class UtilisateursController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $utilisateurs = Utilisateur::all();

        return view('utilisateurs.index', compact('utilisateurs'));
    }

    public function connection(){
        return view('auth/login');
    }

    /**
     * Tenter une connexion.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request){

        //Valider que la request contient bien un courriel et un mot de passe
        $validated = $request->validate([
            'courriel' => 'Required|min:2|max:200',
            'password' => 'Required|min:8|max:200',
        ]);

        $user = array(
            'courriel'=>$request->get('courriel'),
            'password'=>$request->get('password')
        );
        //Tentative de connexion
        if(Auth::attempt($user,$request->get('remember')))
        {
            $utilisateurs = Utilisateur::where('courriel', $request->courriel)->get();

            foreach ($utilisateurs as $utilisateur)
            {
                Session::put('prenom', $utilisateur->prenom);
                Session::put('nom', $utilisateur->nom);
                Session::put('courriel', $utilisateur->courriel);
                Session::put('utilisateur', $utilisateur->id);
                Session::put('admin', $utilisateur->is_admin);
            }

            //Connexion accepté
            if(str_contains($request->get('redirect'),'/connexion') || !$request->has('redirect'))    //Si le lien précédent est vers la page de connexion, renvoyer vers la page d'acceuil
                return redirect()->route('acceuil');
            else    //Rediriger vers la page précédente
                return redirect($request->get('redirect'));
        }
        //Connexion refusé
        return redirect()->route('connexion')->with('error', 'Le email ou le mot de passe n\'est pas valide. ')->withInput();
    }

    /**
     * Se déconnecter de son compte.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(){
        Auth::logout();
        return redirect()->route('acceuil')->with('message','Vous êtes maintenant déconnecté.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $typeUtilisateurs = TypesUtilisateur::all();
        return view('utilisateurs.create', compact('typeUtilisateurs'));
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
            'nom'                 =>'Required|min:2|max:100',
            'prenom'              =>'Required|min:2|max:100',
            'courriel'            =>'Required|email|min:2|max:200',
            'telephone'           =>'max:12|Regex:/[0-9]{3}-[0-9]{3}-[0-9]{4}/',
            'types_utilisateur_id'=>'Required|numeric|integer',
            'password'            =>'Required|min:8|max:15|confirmed',
        ];
        $messages = [
            'courriel.required' => 'Le courriel est obligatoire.',
            'courriel.email'    => 'Le courriel n\'est pas valide.',
            'courriel.min'      => 'Le courriel doit contenir un minimum de 2 caractères.',
            'courriel.max'      => 'Le courriel doit contenir un maximum de 200 caractères.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min'      => 'Le mot de passe doit contenir un minimum de 8 caractères.',
            'password.max'      => 'Le mot de passe doit contenir un maximum de 15 caractères.',
            'password.confirmed'=> 'Les mots de passe entrés doivent être identiques.',
            'nom.min'           => 'Le nom de l\'utilisateur doit contenir un minimum de 2 caractères.',
            'nom.max'           => 'Le nom de l\'utilisateur doit contenir un maximum de 100 caractères.',
            'nom.required'      => 'Le nom de l\'utilisateur est obligatoire.',
            'prenom.min'        => 'Le prénom de l\'utilisateur doit contenir un minimum de 2 caractères.',
            'prenom.max'        => 'Le prénom de l\'utilisateur doit contenir un maximum de 100 caractères.',
            'prenom.required'   =>'Le prénom de l\'utilisateur est obligatoire.',
            'telephone.regex'   => 'Numéro de téléphone invalide',
            'telephone.max'     => 'Le numéro de téléphone doit contenir un maximum de 15 caractères.',
            'types_utilisateur_id.required' => 'Vous devez sélectionner un type d\'utilisateur',
            'types_utilisateur_id.integer'  => 'Le type d\'utilisateur sélectionné n\'est pas valide',
            'types_utilisateur_id.numeric'  => 'Le type d\'utilisateur sélectionné n\'est pas valide',
        ];

        $validated = Validator::make($request->all(), $rules, $messages);

        if($validated->fails())
        {
            $errors = $validated->messages();
            return redirect()->route('utilisateurs.create')->withErrors($errors)->withInput();
        }

        $utilisateur = new Utilisateur;
        // Ajout de l'utilisateur dans la base de données
        try
        {
           $utilisateur->nom                  = $request->nom;
           $utilisateur->prenom               = $request->prenom;
           $utilisateur->courriel             = $request->courriel;
           $utilisateur->telephone            = $request->telephone;
           $utilisateur->types_utilisateur_id = $request->types_utilisateur_id;
           $utilisateur->is_admin             = isset($request->is_admin[0]) ? 1 : 0;
           $utilisateur->password             = Hash::make($request->password);

           $utilisateur->save();
        }
        catch(\Throwable $e)
        {
            $errors = array("Erreur # ".$e->getCode()." -> ".$e->getMessage());
            return redirect()->route('utilisateurs.index')->withErrors($errors);
        }
        return redirect()->route('utilisateurs.index');
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
        $utilisateur = Utilisateur::find($id);
        $typeUtilisateurs = TypesUtilisateur::all();
        return view('utilisateurs.edit', compact('utilisateur','typeUtilisateurs'));
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
        try{
            $rules = [
                'nom'                 =>'Required|min:2|max:100',
                'prenom'              =>'Required|min:2|max:100',
                'courriel'            =>'Required|email|min:2|max:200',
                'telephone'           =>'max:15',
                'types_utilisateur_id'=>'Required|numeric|integer',
            ];
            $messages = [
                'courriel.required' => 'Le courriel est obligatoire.',
                'courriel.email'    => 'Le courriel n\'est pas valide.',
                'courriel.min'      => 'Le courriel doit contenir un minimum de 2 caractères.',
                'courriel.max'      => 'Le courriel doit contenir un maximum de 200 caractères.',
                'nom.min'           => 'Le nom de l\'utilisateur doit contenir un minimum de 2 caractères.',
                'nom.max'           => 'Le nom de l\'utilisateur doit contenir un maximum de 100 caractères.',
                'nom.required'      => 'Le nom de l\'utilisateur est obligatoire.',
                'prenom.min'        => 'Le prénom de l\'utilisateur doit contenir un minimum de 2 caractères.',
                'prenom.max'        => 'Le prénom de l\'utilisateur doit contenir un maximum de 100 caractères.',
                'prenom.required'   =>'Le prénom de l\'utilisateur est obligatoire.',
                'telephone.max'     => 'Le numéro de téléphone doit contenir un maximum de 15 caractères.',
                'types_utilisateur_id.required' => 'Vous devez sélectionner un type d\'utilisateur',
                'types_utilisateur_id.integer'  => 'Le type d\'utilisateur sélectionné n\'est pas valide',
                'types_utilisateur_id.numeric'  => 'Le type d\'utilisateur sélectionné n\'est pas valide',
            ];

            $validated = Validator::make($request->all(), $rules, $messages);

            if($validated->fails())
            {
                $errors = $validated->messages();
                return redirect()->route('utilisateurs.edit',$id)->withErrors($errors)->withInput();
            }

            $utilisateur = Utilisateur::find($id);
            $utilisateur->update($request->all());
            $utilisateur->is_admin = isset($request->is_admin[0]) ? 1 : 0;

            $utilisateur->touch();
            $utilisateur->save();

            return redirect()->route('utilisateurs.index');
        }
        catch(\Throwable $e)
        {
            $errors = array("Une erreur s'est produite lors de la modification. Erreur #".$e->getCode());
            return redirect()->route('utilisateurs.edit',$id)->withErrors($errors)->withInput();
        }
    }

    public function resetLogin($id)
    {
        $utilisateur = Utilisateur::find($id);

        return view('utilisateurs.resetlogin', compact('utilisateur'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateLogin(Request $request, $id)
    {
        $rules = [
            'password'            =>'Required|min:8|max:15|confirmed',
        ];
        $messages = [
            'password.required'   => 'Le mot de passe est obligatoire.',
            'password.min'        => 'Le mot de passe doit contenir un minimum de 8 caractères.',
            'password.max'        => 'Le mot de passe doit contenir un maximum de 15 caractères.',
            'password.confirmed'  => 'Les mots de passe entrés doivent être identiques.',
        ];

        $validated = Validator::make($request->all(), $rules, $messages);

        if($validated->fails())
        {
            $errors = $validated->messages();
            return redirect()->route('utilisateurs.resetLogin', $id)->withErrors($errors);
        }

        $utilisateur = Utilisateur::find($id);

        try
        {
            $utilisateur->password = Hash::make($request->password);
            $utilisateur->save();
        }
        catch(\Throwable $e)
        {
            $errors = array("Erreur # ".$e->getCode()." -> ".$e->getMessage());
            return redirect()->route('utilisateurs.resetLogin', $id)->withErrors($errors);
        }
        return redirect()->route('utilisateurs.index');
    }

    public function resetPasswd($id)
    {
        $utilisateur = Utilisateur::find($id);

        return view('utilisateurs.resetpasswd', compact('utilisateur'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePasswd(Request $request, $id)
    {
        $rules = [
            'cur_passwd'          =>'Required|min:8|max:15',
            'password'            =>'Required|min:8|max:15|confirmed|different:cur_passwd',
        ];
        $messages = [
            'cur_passwd.required' => 'Le mot de passe actuel est obligatoire.',
            'cur_passwd.min'      => 'Le mot de passe actuel doit contenir un minimum de 8 caractères.',
            'cur_passwd.max'      => 'Le mot de passe actuel doit contenir un maximum de 15 caractères.',
            'password.required'   => 'Le mot de passe est obligatoire.',
            'password.min'        => 'Le mot de passe doit contenir un minimum de 8 caractères.',
            'password.max'        => 'Le mot de passe doit contenir un maximum de 15 caractères.',
            'password.confirmed'  => 'Les nouveaux mots de passe entrés doivent être identiques.',
            'password.different'  => 'L\'ancien et le nouveau mot de passe doivent être différents.',
        ];

        $validated = Validator::make($request->all(), $rules, $messages);

        if($validated->fails())
        {
            $errors = $validated->messages();
            return redirect()->route('utilisateurs.create')->withErrors($errors);
        }

        if(!Hash::check($request->get('cur_passwd'), Auth::user()->password))
        {
            $errors = array("Impossible de modifier le mot de passe, veuillez réessayer.");
            return redirect()->route('utilisateurs.resetLogin')->withErrors($errors);
        }

        try
        {
            $utilisateur = Utilisateur::find($id);
            $utilisateur->password = Hash::make($request->password);
            $utilisateur->save();
        }
        catch(\Throwable $e)
        {
            $errors = array("Erreur # ".$e->getCode()." -> ".$e->getMessage());
            return redirect()->route('utilisateurs.resetLogin')->withErrors($errors);
        }
        return redirect()->route('acceuil');
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
