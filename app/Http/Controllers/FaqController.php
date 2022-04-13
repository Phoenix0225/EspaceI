<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\FaqGroupe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faqGroupes = FaqGroupe::whereHas('faq')->get();
        $faqs       = Faq::all();

        return view('faq.faq', compact('faqGroupes', 'faqs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $faqGroupes = FaqGroupe::all();

        return view('faq.create', compact('faqGroupes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'question'      => 'Required|min:2|max:150',
            'reponse'       => 'Required|min:2',
        ];

        $messsages = [
            'question.required'         => 'Une question est requise',
            'question.min'              => 'Veuillez entrer une question de plus de 2 caractères',
            'question.max'              => "Veuillez entrer une question d'un maximum de 150 caractères",
            'reponse.required'          => 'Une réponse est requise',
            'reponse.min'               => 'Veuillez entrer une réponse de plus de 2 caractères',
            'nomGroupeFaq.required'     => 'Veuillez entrer une catégorie',
            'nomGroupeFaq.min'          => 'Veuillez entrer une catégorie de plus de 2 caractères',
            'faq_groupe_id.required'    => 'Veuillez choisir une catégorie',
        ];

        if ($request->nomGroupeFaq == null) {
            $rules['faq_groupe_id'] = 'Required';
            $nouvCategorieIsEmpty = true;
        }
        else {
            $rules['nomGroupeFaq'] = 'Required|min:2';
            $nouvCategorieIsEmpty = false;
        }

        $validator = Validator::make($request->all(), $rules, $messsages);

        if ($validator->fails()) {
            $errors = json_encode(array(
                'message' => $validator->messages(),
                'type' => 'error',
            ));
            return response($errors);
       }

        //Ajout de l'entrée
        $faq = new Faq;
        $faq->question = $request->question;
        $faq->reponse = $request->reponse;

        if($nouvCategorieIsEmpty) {
            $faq->faq_groupe_id = $request->faq_groupe_id;
        }
        else {
            $faqGroupe = new FaqGroupe;
            $faqGroupe->groupe = $request->nomGroupeFaq;

            try
            {
                $faqGroupe->save();
            }
            catch(\Throwable $e)
            {
                $errors = array("Erreur # ".$e->getCode()." -> ".$e->getMessage());
                return redirect()->back()->withErrors($errors);
            }

            $faq->faq_groupe_id = $faqGroupe->id;
        }

        try
        {
            $faq->save();
        }
        catch(\Throwable $e)
        {
            $errors = array("Erreur # ".$e->getCode()." -> ".$e->getMessage());
            return redirect()->back()->withErrors($errors);
        }

        $message = json_encode(array(
            'message' => 'Question ajouté!',
            'type' => 'success',
        ));

        return response($message);
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
        try
        {
            Faq::find($id)->delete();
        }
        catch(\Throwable $e)
        {
            $errors = array("Erreur # ".$e->getCode()." -> ".$e->getMessage());
            return redirect()->back()->withErrors($errors);
        }

        return response('Question supprimé!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyGroupe($id)
    {

        try
        {
            FaqGroupe::find($id)->faq()->delete();
            FaqGroupe::find($id)->delete();
        }
        catch(\Throwable $e)
        {
            $errors = array("Erreur # ".$e->getCode()." -> ".$e->getMessage());
            return redirect()->back()->withErrors($errors);
        }

        return response('Groupe de questions supprimé!');
    }
}
