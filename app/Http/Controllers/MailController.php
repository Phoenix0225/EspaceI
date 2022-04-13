<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Swift_Mailer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailController extends Controller
{
    public function confirmation_email() {
         $user = Auth::user();

         $to_name = $user->prenom.' '.$user->nom;
         $to_email = $user->courriel;
         $data = array('name'=>config('app.name', 'Laravel'), 'body' => "Confirmation de rendez-vous.");
         Mail::send('emails.confirmation', $data, function($message) use ($to_name, $to_email) {
         $message->to($to_email, $to_name)->subject('Confirmation de rendez-vous');
         $message->from('espacei@cegeptr.qc.ca',config('app.name', 'Laravel'));
         });

         echo 'mail envoyé';
        
     }

     public function contact_email(Request $request){
        try{
            $rules = [
                'name'                 =>'Required|min:2|max:100',
                'telephone'           =>'max:15',
                'email'            =>'Required|email|min:2|max:200',
                'message'           => 'Required|min:25'
            ];
            $messages = [
                'courriel.required' => 'Le courriel est obligatoire.',
                'courriel.email'    => 'Le courriel n\'est pas valide.',
                'courriel.min'      => 'Le courriel doit contenir un minimum de 2 caractères.',
                'courriel.max'      => 'Le courriel doit contenir un maximum de 200 caractères.',
                'nom.min'           => 'Le nom doit contenir un minimum de 2 caractères.',
                'nom.max'           => 'Le nom doit contenir un maximum de 100 caractères.',
                'nom.required'      => 'Le nom est obligatoire.',
                'telephone.max'     => 'Le numéro de téléphone doit contenir un maximum de 15 caractères.',
                'message.required'  => 'Le message est obligatoire.',
                'message.min'       => 'Le message doit être composé d\'un minimum de 25 caractères.'
            ];
            $validated = Validator::make($request->all(), $rules, $messages);

            if($validated->fails()){
                $errors = $validated->messages();
                return redirect()->back()->withErrors($errors);
            }
            $to_name = config('app.name', 'Laravel');
            $to_email = env('MAIL_USERNAME');
            $data = array('name'=>config('app.name', 'Laravel'), 'body' => "Confirmation de rendez-vous.");
            $message = 'Vous avez reçu un nouveau message de '.$request->get('name').', '.$request->get('email');
            if($request->has('telephone'))
                $message = $message.' ('.$request->get('telephone').')';
            $message = $message.': '.$request->get('message');
            Mail::raw($message, function($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->subject('Nouveau message');
                $message->from('espacei@cegeptr.qc.ca',config('app.name', 'Laravel'));
            });

            return redirect()->back();

        }catch(\Throwable $e){
            Log::debug($e);
            $errors = array("Une erreur s'est produite, veuillez réessayer plutard.");
           return redirect()->back()->withErrors($errors);
        }
     }
}
