<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BilletController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ParametreController;
use App\Http\Controllers\RendezVousController;
use App\Http\Controllers\TutorielController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UtilisateursController;
use App\Http\Controllers\DisponibiliteController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\AccueilCommentaireController;
use App\Mail\ConfirmationParticipant;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Page principale
Route::get('/',
    [HomeController::class, 'index'])->name('acceuil');

//Page de connexion
Route::get('/connexion', function () {
    return view('auth/login');
})->name('connexion');

//Validation de la connection
Route::post('/connexion',
    [UtilisateursController::class, 'login'])->name('connexion.login');

//Déconnexion
Route::get('/deconnexion',
    [UtilisateursController::class,'logout'])->name('connexion.logout')->middleware('auth');

//Page du Dashboard
Route::get('/dashboard/{id}',
    [DashboardController::class, 'index'])->name('dashboard.index')->middleware('auth');

/************** R O U T E S  P O U R  L E S  R E N D E Z - V O U S  ******************/

//Page qui affiche la liste des rendez-vous
Route::get('/rendez-vous',[RendezVousController::class,'index'])->name('appointment.index')->middleware('auth');

//Page qui affiche un seul rendez-vous
Route::get('/rendez-vous/{id}',[RendezVousController::class,'show'])->name('appointment.show')->middleware('auth');

// Suppression d'un rendez-vous
Route::post('/rendez-vous/supprimer/{id}',[RendezVousController::class,'destroy'])->name('appointment.delete')->middleware('auth');

//Page de sélection de catégorie de rendez-vous
Route::get('/rendez-vous/nouveau/categorie',
    [RendezVousController::class, 'selectCategory'])->name('appointment.category');

//Page de sélection de la date du rendez-vous
Route::get('/rendez-vous/nouveau/{id}',
    [RendezVousController::class, 'selectDate'])->name('appointment.date');

//Page de création d'un rendez-bous
Route::post('/rendez-vous/nouveau',
    [RendezVousController::class, 'create'])->name('appointment.create');

//Création du rendez-vous
Route::post('/rendez-vous/nouveau/ajout',
    [RendezVousController::class, 'store'])->name('appointment.store');

//Courriel de confirmation de rendez-vous
Route::get('/rendez-vous/confirmation/{token}',[RendezVousController::class,'confirm'])->name('appointment.confirm');

//Annuler un rendez-vous à partir du courriel
Route::get('/rendez-vous/annuler/{token}',[RendezVousController::class,'cancel'])->name('appointment.destroy');


/************** R O U T E S  P O U R  L E S   É V É N E M E N T S  ******************/

//Page des événements
Route::get('/evenement',
    [EvenementController::class, 'index'])->name('evenement');

//Page de création d'événement
Route::get('evenement/create_evenement',
    [EvenementController::class, 'create'])->name('create_event')->middleware('auth');

//Création d'événement
Route::post('/evenement',
    [EvenementController::class, 'store'])->name('evenement.store')->middleware('auth');

//Page de modification d'événement
Route::get('/evenement/modifier/{id}',
    [EvenementController::class, 'edit'])->name('evenement_edit')->middleware('auth');

//Modification d'un événement
Route::post('/evenement/modifier/{id}',
    [EvenementController::class, 'update'])->name('evenement.update')->middleware('auth');

//Delete un evenement
Route::get('/evenement/destroy/{id}',
    [EvenementController::class, 'destroy'])->name('evenement.destroy')->middleware('auth');


/************** R O U T E S  P O U R  L E S   I N S C R I P T I O N  ******************/

//Ajouter un participant
Route::post('/inscription',
    [ParticipantController::class, 'store'])->name('participant.store');

//Courriel de confirmation après l'inscription
Route::get('/inscription/confirm/{token}',
    [ParticipantController::class, 'confirm'])->name('participant.confirm');

//Annulation d'une inscripton
Route::get('/inscription/destroy/{token}',
    [ParticipantController::class, 'destroy'])->name('participant.destroy');

//Affiche la liste des participants à un événement
Route::get('/inscription/liste/{id}',
    [ParticipantController::class, 'index'])->name('participant.index')->middleware('auth');

//Delete un participant
Route::get('/inscription/delete/{id}',
    [ParticipantController::class, 'delete'])->name('participant.delete')->middleware('auth');

/********************** R O U T E S  P O U R  L E S  F A Q **************************/

//Page FAQ
Route::get('/faq',
    [FaqController::class, 'index'])->name('faq');

//Page FAQ create
Route::get('/faq/create',
    [FaqController::class, 'create'])->name('faq.create')->middleware(['auth', 'admin']);

//Page FAQ store
Route::post('/faq/store', [
    FaqController::class, 'store'])->name('faq.store')->middleware(['auth', 'admin']);

//Supprime une question de la FAQ
Route::delete('/faq/destroy/{id}',
    [FaqController::class, 'destroy'])->name('faq.destroy')->middleware(['auth', 'admin']);

//Supprime une entrer de la FAQ
Route::delete('/faqGroupe/destroy/{id}',
    [FaqController::class, 'destroyGroupe'])->name('faqGroupe.destroy')->middleware(['auth', 'admin']);

/*************** R O U T E S  P O U R  L E S   T U T O R I E L S  *******************/


/********** R O U T E S  P O U R  L E S   D I S P O N I B I L I T E S  **************/

//Page d'affichage des horaires
Route::get('disponibilites',
    [DisponibiliteController::class, 'index'])->name('disponibilites.index');

//Page d'ajout de disponibilités
Route::get('disponibilites/create',
    [DisponibiliteController::class, 'create'])->name('disponibilites.create')->middleware(['auth', 'admin']);

//Page qui affiche une plage horaire
Route::get('disponibilites/{id}',
    [DisponibiliteController::class, 'edit'])->name('disponibilites.edit')->middleware(['auth', 'admin']);

//Ajout d'une plage horaire
Route::post('disponibilites/store',
    [DisponibiliteController::class, 'store'])->name('disponibilites.store')->middleware(['auth', 'admin']);

//Ajout de plusieurs plage horaire
Route::post('disponibilites/store_recur',
    [DisponibiliteController::class, 'store_recur'])->name('disponibilites.store_recur')->middleware(['auth', 'admin']);

//Modifier une plage horaire
Route::patch('disponibilites/{id}/update',
    [DisponibiliteController::class, 'update'])->name('disponibilites.update')->middleware(['auth', 'admin']);

//Supprimer une plage horaire
Route::delete('disponibilites/{id}/destroy',
    [DisponibiliteController::class, 'destroy'])->name('disponibilites.destroy')->middleware(['auth', 'admin']);

/************ R O U T E S  P O U R  L E S   U T I L I S A T E U R S  ****************/

//Page d'affichage des utilisateurs
Route::get('utilisateurs',
    [UtilisateursController::class, 'index'])->name('utilisateurs.index')->middleware(['auth', 'admin']);

//Page de création d'utilisateur
Route::get('utilisateurs/create',
    [UtilisateursController::class, 'create'])->name('utilisateurs.create')->middleware(['auth', 'admin']);

//Ajout d'un utilisateur
Route::post('utilisateurs',
    [UtilisateursController::class, 'store'])->name('utilisateurs.store')->middleware(['auth', 'admin']);

//Page de modification des utilisateurs
Route::get('utilisateurs/modifier/{id}',
    [App\Http\Controllers\UtilisateursController::class, 'edit'])->name('utilisateurs.edit')->middleware(['auth', 'admin']);

//Page de reset mot de passe par l'administrateur
Route::get('utilisateurs/resetlogin/{id}',
    [App\Http\Controllers\UtilisateursController::class, 'resetLogin'])->name('utilisateurs.resetLogin')->middleware(['auth', 'admin']);

// Reset le mot de passe
Route::patch('utilisateurs/updatelogin/{id}',
    [App\Http\Controllers\UtilisateursController::class, 'updateLogin'])->name('utilisateurs.updateLogin')->middleware(['auth', 'admin']);

//Page de modification de mot de passe par l'utilisateur
Route::get('utilisateurs/resetpasswd/{id}',
    [App\Http\Controllers\UtilisateursController::class, 'resetPasswd'])->name('utilisateurs.resetPasswd')->middleware('auth');

//Modifier le mot de passe
Route::patch('utilisateurs/updatepasswd/{id}',
    [App\Http\Controllers\UtilisateursController::class, 'updatePasswd'])->name('utilisateurs.updatePasswd')->middleware('auth');

// Modification d'utilisateur
Route::post('utilisateurs/modifier/{id}',
    [App\Http\Controllers\UtilisateursController::class, 'update'])->name('utilisateurs.update')->middleware(['auth', 'admin']);

/************ R O U T E S  P O U R  L E S  P A R A M E T R E S  ****************/

// Page des paramètres
Route::get('/parametres',
    [ParametreController::class, 'index'])->name('parametres')->middleware(['auth', 'admin']);

// Modification des paramètres
Route::post('/parametres/{id}',
    [ParametreController::class, 'update'])->name('parametres.update')->middleware(['auth', 'admin']);

//Suppression d'un problème
Route::delete('/probleme/destroy/{id}',
    [ParametreController::class, 'probleme_destroy'])->name('probleme.destroy')->middleware(['auth', 'admin']);

// Suppression d'un endroit
Route::delete('/endroit/destroy/{id}',
    [ParametreController::class, 'endroit_destroy'])->name('endroit.destroy')->middleware(['auth', 'admin']);

// Suppression d'un type d'événement
Route::delete('/type_event/destroy/{id}',
    [ParametreController::class, 'type_event_destroy'])->name('type_event.destroy')->middleware(['auth', 'admin']);


/************** R O U T E S  P O U R  L E S  E M A I L S  ******************/

Route::post('/contactez-nous',
    [MailController::class, 'contact_email'])->name('email.contact');

Route::get('/email', function() {
    return new ConfirmationParticipant();
});

/************ R O U T E S  P O U R  L E S  B I L L E T S  ****************/

// Page qui affiche les billets
Route::get('/billets',
    [BilletController::class, 'index'])->name('billets')->middleware('auth');

// Page de création de billet
Route::get('/billets/create',
    [BilletController::class, 'create'])->name('billets.create');

// Page de modification de billet
Route::get('/billets/{id}',
    [BilletController::class, 'edit'])->name('billets.edit')->middleware('auth');

// Création d'un billet
Route::post('/billets/store',
    [BilletController::class, 'store'])->name('billets.store');

// Modiciation d'un billet
Route::post('/billets/update/{id}',
    [BilletController::class, 'update'])->name('billets.update')->middleware('auth');

// Ajout d'un commentaire
Route::post('/billets/storeCommentaire',
    [BilletController::class, 'storeCommentaire'])->name('billets.storeCommentaire')->middleware('auth');

// Suppression d'un billet
Route::get('/billet_categories/destroy/{id}',
    [BilletController::class, 'destroyBilletCategorie'])->name('billet_categories.destroy')->middleware('auth');

/************ R O U T E S  P O U R  L E S  T U T O R I E L S  ****************/

// Page des tutoriels
Route::get('/tutoriels',
    [TutorielController::class, 'index'])->name('tutoriels');
