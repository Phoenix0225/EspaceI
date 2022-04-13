@extends('layouts.app')

@section('script')
    <script type="text/javascript" src="{{ asset('js/utilisateurs.js') }}"></script>
    <script src="{{ asset('js/jquery-input-mask-phone-number.min.js') }}"></script>
@endsection

@section('content')
    <body class="sub_page">
        <section class="login_section layout_padding">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="login_form">
                            <h5>
                                Entrez les informations a modifier
                            </h5>
                            <form method="post" action="{{route('utilisateurs.update',$utilisateur->id)}}">
                                @csrf
                                <div>
                                    <input type="text" placeholder="Nom de l'utilisateur" value="{{ $utilisateur->nom }}" name="nom" id="nom" required minlength="2" maxlength="100"/>
                                </div>
                                <div>
                                    <input type="text" placeholder="Prénom de l'utilisateur" value="{{ $utilisateur->prenom }}" name="prenom" id="prenom" required minlength="2" maxlength="100"/>
                                </div>
                                <div>
                                    <input type="email" placeholder="Adresse courriel" value="{{ $utilisateur->courriel }}" id="courriel" name="courriel" required minlength="2" maxlength="200"/>
                                </div>
                                <div>
                                    <input class="modal-input phone" type="text" placeholder="Téléphone" name="telephone" value="{{ $utilisateur->telephone }}" id="telephone" maxlength="15"/>
                                </div>
                                <select class="comboBoxForm" name="types_utilisateur_id"  id="types_utilisateur_id" required>
                                    <option class="comboBoxSelect" value="0">---Sélection---</option>
                                    @if(count($typeUtilisateurs))
                                        @foreach($typeUtilisateurs as $typeUtilisateur)
                                            <option class="comboBoxForm" value="{{$typeUtilisateur->id}}" @if($typeUtilisateur->id == $utilisateur->types_utilisateur_id) selected @endif>{{$typeUtilisateur->type}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="form-check">
                                    <input class="form-check-input fb_chk-box" type="checkbox" name="is_admin" id="is_admin" @if($utilisateur->is_admin) checked @endif>
                                    <label class="form-check-label" for="is_admin">
                                        L'utilisateur est un administrateur
                                    </label>
                                </div>
                                <button type="submit">Modifier l'utilisateur</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
@endsection
