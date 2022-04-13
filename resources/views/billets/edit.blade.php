@extends('layouts.app')

@section('script')
    <script type="text/javascript" src="{{ asset('js/billets.js') }}"></script>
@endsection

@section('style')
    <link href="{{ asset('css/cedric.css') }}" rel="stylesheet">
@endsection

@section('content')

    <section>
        <form action="{{ route('billets.update', ['id' => $billet->id]) }}" method="POST" class="border border-secondary rounded p-4 mx-lg-5 mx-md-3 mx-sm-0 mt-lg-5 mt-md-3 mt-sm-0 mb-5">
            @csrf
            <div class="input-group mb-3">
                <div class="input-group-prepend col-lg-2 col-md-12 col-sm-12 p-0">
                    <span class="input-group-text w-100 petit-left-round-0" id="id-billet"># {{ $billet->id }}</span>
                </div>
                <div class="col-lg-10 col-md-12 col-sm-12 p-0">
                    <input type="text" class="form-control rounded-0 petit-top-br-0 border-rounded-right" name="titre" placeholder="Titre" aria-label="Titre" aria-describedby="id-billet" value="{{ old('titre') ? old('titre') : $billet->titre }}">
                </div>
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend col-lg-2 col-md-12 col-sm-12 p-0">
                    <span class="input-group-text w-100 petit-left-round-0" id="par-billet">Par :</span>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12 p-0">
                    <input type="text" class="form-control rounded-0 petit-top-br-0" name="nom_client" placeholder="Nom du client" aria-label="Nom_Client" aria-describedby="par-billet" value="{{ old('nom_client') ? old('nom_client') : $billet->nom_client }}">
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 p-0">
                    <input type="text" class="form-control rounded-0 border-right-0 border-left-0" id="courriel-billet" name="courriel" placeholder="Courriel" aria-label="Courriel" value="{{ old('courriel') ? old('courriel') : $billet->courriel }}">
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12 p-0">
                    <input type="text" class="form-control rounded-0 border-rounded-right" name="telephone" placeholder="Téléphone" aria-label="Telephone" value="{{ old('telephone') ? old('telephone') : $billet->telephone }}">
                </div>
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend col-lg-2 col-md-12 col-sm-12 p-0">
                    <label class="input-group-text w-100 petit-left-round-0" for="utilisateur-billet">Assigné à</label>
                </div>
                <div class="col-lg-10 col-md-12 col-sm-12 p-0">
                    <select class="custom-select rounded-0 petit-top-br-0 border-rounded-right" name="utilisateur_id" id="utilisateur-billet">
                        <option value="">Aucun employé</option>
                        @foreach($utilisateurs as $utilisateur)
                            @if($utilisateur-> id != 1)
                                <option value="{{ $utilisateur->id }}" {{ $utilisateur->id == $billet->utilisateur_id ? 'selected' : '' }}>{{ $utilisateur->nom }}, {{ $utilisateur->prenom }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="d-flex flex-wrap">
                <div class="input-group mb-3 col-lg col-md-12 col-sm-12 p-0">
                    <div class="input-group-prepend col-lg-6 col-md-12 col-sm-12 p-0">
                        <span class="input-group-text w-100 petit-left-round-0" id="created-at-billet">Date de création</span>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 p-0">
                        <input type="text" class="form-control rounded-0 petit-top-br-0 border-rounded-right bg-white" name="created_at"  disabled aria-label="Date de création" aria-describedby="created-at-billet" value="{{ $billet->created_at }}">
                    </div>
                </div>
                <div class="col-lg-1"></div>
                <div class="input-group mb-3 col-lg col-md-12 col-sm-12 p-0">
                    <div class="input-group-prepend col-lg-6 col-md-12 col-sm-12 p-0">
                        <span class="input-group-text w-100 petit-left-round-0" id="updated-at-billet">Dernière modification</span>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 p-0">
                        <input type="text" class="form-control rounded-0 petit-top-br-0 border-rounded-right bg-white" name="updated_at"  disabled aria-label="Dernière modification" aria-describedby="updated-at-billet" value="{{ $billet->updated_at }}">
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <div class="row text-center">
                    <div class="col">
                        Catégorie
                    </div>
                    <div class="col">
                        Statut
                    </div>
                    <div class="col">
                        Priorité
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <select class="custom-select" name="billet_categorie_id" id="categorie-billet">
                    @foreach($billetCategories as $billetCategorie)
                        <option value="{{ $billetCategorie->id }}" {{ $billetCategorie->id == $billet->billet_categorie_id ? 'selected' : '' }}>{{ $billetCategorie->categorie }}</option>
                    @endforeach
                </select>
                <select class="custom-select" name="billet_statut_id" id="statut-billet">
                    @foreach($statuts as $statut)
                        <option value="{{ $statut->id }}" {{ $statut->id == $billet->billet_statut_id ? 'selected' : '' }}>{{ $statut->statut }}</option>
                    @endforeach
                </select>
                <select class="custom-select" name="priorite_id" id="priorite-billet">
                    @foreach($priorites as $priorite)
                        <option value="{{ $priorite->id }}" {{ $priorite->id == $billet->priorite_id ? 'selected' : '' }}>{{ $priorite->priorite }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mt-4">
                Description
            </div>
            <textarea class="form-control mb-1" id="description-billet" name="description_billet" rows="3">{{ $billet->description_billet }}</textarea>
            <div class="d-flex flex-row-reverse">
                <a href="{{ url()->current() }}" class="btn btn-outline-danger ml-1">Annuler</a>
                <button type="submit" class="btn btn-outline-success">Sauvegarder</button>
            </div>
        </form>
        <h3 class="ml-lg-5 ml-md-3 ml-sm-0">
            Commentaires
        </h3>
        <div class="border border-secondary rounded p-4 mx-lg-5 mx-md-3 mx-sm-0 mb-5">
            <form>
                <input type="hidden" name="utilisateur_id" id="utilisateur_id" value="{{ auth()->user()->id }}">
                <input type="hidden" name="billet_id" id="billet_id" value="{{ $billet->id }}">
                <textarea class="form-control mb-1" id="commentaire" placeholder="Nouveau commentaire pour le billet #{{ $billet->id }}" name="commentaire" rows="3">{{ old('commentaire') }}</textarea>
                <button type="button" class="btn btn-outline-success mb-3 ml-auto d-block" id="newCommentBillet" data-token="{{ csrf_token() }}">Envoyer</button>
            </form>
            <div id="comments-billet">
                @foreach($commentaires as $commentaire)
                    <div class="comment-billet">
                        <p class="font-weight-bold d-inline">{{ $commentaire->prenom }} {{ $commentaire->nom }}</p><p class="d-inline"> @ {{ $commentaire->created_at }}</p>
                        <p class="ml-3">{{ $commentaire->commentaire }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
