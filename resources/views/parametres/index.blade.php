@extends('layouts.app')

@section('script')
    <script type="text/javascript" src="{{ asset('js/parametres.js') }}"></script>
@endsection

@section('style')
    <link href="{{ asset('css/cedric.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
@endsection

@section('content')

    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="7500">
        <div class="toast-header">
            <i class="icon-toast bi mr-1 text-white"></i>
            <strong class="titre-toast mr-auto text-white"></strong>
        </div>
        <div class="toast-body text-white" id="message-toaster"></div>
    </div>

    <section class="event_section layout_padding create-tile p-0">
        <div class="container pt-5">
            <div class="heading_container">
                <h3>
                    Paramètres
                </h3>
                <p>
                    Configurer les éléments paramétrables de l'Espace I
                </p>
            </div>

{{--            Menu des parametres     --}}
            <div class="container">
                <form action="{{ route('parametres.update', 1) }}" method="POST">
                    @csrf
                    <div class="row flex-wrap">
                        <div class="col-lg-3 col-md-3 col-sm-12 pl-0">
                            <div class="tbl-settings w-100">
                                <table class="table">
                                    <tbody>
                                        <tr class="table-info table-settings-options" id="settings-horaire-btn">
                                            <td>Horaire</td>
                                        </tr>
                                        <tr class="table-info table-settings-options" id="settings-rendez-vous-btn">
                                            <td>Rendez-vous</td>
                                        </tr>
                                        <tr class="table-info table-settings-options" id="settings-accueil-btn">
                                            <td>Page d'accueil</td>
                                        </tr>
                                        <tr class="table-info table-settings-options" id="settings-dashboard-btn">
                                            <td>Tableau de bord</td>
                                        </tr>
                                        <tr class="table-info table-settings-options" id="settings-tutoriel-btn">
                                            <td>Tutoriels</td>
                                        </tr>
                                        <tr class="table-info table-settings-options" id="settings-billets-btn">
                                            <td>Billets</td>
                                        </tr>
                                        <tr class="table-info table-settings-options" id="settings-event-btn">
                                            <td>Événements</td>
                                        </tr>
                                        <tr class="table-info table-settings-options" id="settings-endroit-btn">
                                            <td>Endroits</td>
                                        </tr>
                                        <tr class="table-info table-settings-options" id="settings-autres-btn">
                                            <td>Autres paramètres</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-info w-100" id="btn-save-settings">Enregistrer  <i class="bi bi-save2"></i></button>
                            <br/>
                        </div>
    {{--                    Fin du menu des parametres          --}}

                        <div class="col-lg-9 col-md-9 col-sm-12">
                            <div id="form-settings">
                                <div class="container cache" id="settings-horaire">
                                    <div class="row">
                                        <div class="col-4">
                                            Heure de début
                                        </div>
                                        <div class="col">
                                            <input type="time" name="rdv_heure_debut" value="{{ $parametres->rdv_heure_debut }}">
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-4">
                                            Heure de fin
                                        </div>
                                        <div class="col">
                                            <input type="time" name="rdv_heure_fin" value="{{ $parametres->rdv_heure_fin }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="container cache" id="settings-rendez-vous">
                                    <div class="row">
                                        <div class="col-6">
                                            <a href="{{ route('appointment.index')}}">Voir tous les rendez-vous</a><br/><br/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            Durée de la plage horaire (minutes)
                                        </div>
                                        <div class="col">
                                            <input type="number" min="1" name="duree_plage_horaire" value="{{ $parametres->duree_plage_horaire }}">
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-6">
                                            Durée maximale des rendez-vous (minutes)
                                        </div>
                                        <div class="col">
                                            <input type="number" min="1" name="duree_rdv_max" value="{{ $parametres->duree_rdv_max }}">
                                        </div>
                                    </div>
                                    <br/>
                                    <table class="table" id="table-rdv_categ">
                                        <thead class="table-info">
                                        <tr>
                                            <th colspan="2" scope="col">Types de rendez-vous</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($problemes as $probleme)
                                            <tr class="parent">
                                                <td>
                                                    <div class="d-flex">
                                                        {{$probleme->probleme}}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        <div class="mr-auto">
                                                            {{ $probleme->duree }}, minutes
                                                        </div>
                                                        <div>
                                                            <i class="float-right bi bi-dash-circle-fill supprimer-probleme" data-id="{{ $probleme->id }}" data-token="{{ csrf_token() }}"></i>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr id="row-add-probleme">
                                            <td colspan="2" ><div class="btn btn-outline-success" id="add-rdv_categ">Ajouter</div></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="container cache" id="settings-accueil">
                                    <div class="row">
                                        <div class="col-6">
                                            Nombre d'événements à afficher
                                        </div>
                                        <div class="col">
                                            <input type="number" min="1" name="nb_evenements_accueil" value="{{ $parametres->nb_evenements_accueil }}" required>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-4">
                                            Texte à propos du service
                                        </div>
                                        <div class="col-8">
                                            <textarea class="col-12" name="txt_a_propos_accueil" id="txt_a_propos_accueil" rows="4" >{{ $parametres->txt_a_propos_accueil }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="container cache" id="settings-dashboard">
                                    <div class="row">
                                        <div class="col-6">
                                            Nombre de disponibilités à afficher
                                        </div>
                                        <div class="col">
                                            <input type="number" min="1" name="nb_dispo_dashboard" value="{{ $parametres->nb_dispo_dashboard }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="container cache" id="settings-tutoriel">
                                    <div class="row">
                                        <div class="col-4">
                                            Clé API
                                        </div>
                                        <div class="col-8">
                                            <input type="text" maxlength="100" name="api_key" value="{{ $parametres->api_key }}" >
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-4">
                                            ID de la chaine Youtube
                                        </div>
                                        <div class="col-8">
                                            <input type="text" maxlength="100" name="channel_id" value="{{ $parametres->channel_id }}">
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-4">
                                            Lien de la chaine Youtube
                                        </div>
                                        <div class="col-8">
                                            <input type="text" maxlength="255" name="lien_channel" value="{{ $parametres->lien_channel}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="container cache" id="settings-billets">
                                    <table class="table" id="table-type-billet">
                                        <thead class="table-info">
                                            <tr>
                                                <th scope="col">Types de billet</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($billetCategories as $billetCategorie)
                                                <tr class="parent">
                                                    <td>
                                                        <div class="d-flex">
                                                            <div class="mr-auto">
                                                                {{ $billetCategorie->categorie }}
                                                            </div>
                                                            @if($billetCategorie->id != 1)
                                                                <div>
                                                                    <a href="{{ route('billet_categories.destroy', [$billetCategorie->id]) }}"><i class="float-right bi bi-dash-circle-fill supprimer-type-billet"></i></a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr id="row-add-type">
                                                <td><div class="btn btn-outline-success" id="add-type-billet">Ajouter</div></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="container cache" id="settings-event">
                                    <table class="table" id="table-type_evenement">
                                        <thead class="table-info">
                                            <tr>
                                                <th scope="col">Types d'événements</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($types_evenements as $types_evenement)
                                                <tr class="parent">
                                                    <td>
                                                        <div class="d-flex">
                                                            <div class="mr-auto">
                                                                {{$types_evenement->type}}
                                                            </div>
                                                            <div>
                                                                <i class="float-right bi bi-dash-circle-fill supprimer-type-event" data-id="{{ $types_evenement->id }}" data-token="{{ csrf_token() }}"></i>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr id="row-add-event">
                                                <td><div class="btn btn-outline-success" id="add-type-event">Ajouter</div></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="container cache" id="settings-endroit">
                                    <table class="table" id="table-endroit">
                                        <thead class="table-info">
                                            <tr>
                                                <th colspan="2" scope="col">Endroits</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($endroits as $endroit)
                                                <tr class="parent">
                                                    <td>
                                                        <div class="d-flex">
                                                            {{$endroit->adresse}}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <div class="mr-auto">
                                                                {{ $endroit->lieu }}
                                                            </div>
                                                            <div>
                                                                <i class="float-right bi bi-dash-circle-fill supprimer-endroit" data-id="{{ $endroit->id }}" data-token="{{ csrf_token() }}" ></i>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr id="row-add-endroit">
                                                <td colspan="2"><div class="btn btn-outline-success" id="add-endroit">Ajouter</div></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="container cache" id="settings-autres">
                                    <a href="{{ route('utilisateurs.index')}}">Gestion des utilisateurs</a><br/><br/>
                                    <a href="{{ route('disponibilites.index')}}">Gestion des disponibilités</a><br/><br/>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
