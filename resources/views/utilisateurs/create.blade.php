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
                                Entrez les informations du nouvel utilisateur
                            </h5>
                            <form method="post" action="{{route('utilisateurs.store')}}">
                                @csrf
                                <div>
                                    <input type="text" placeholder="Nom de l'utilisateur" value="{{ old('nom') }}" name="nom" id="nom" required minlength="2" maxlength="100"/>
                                </div>
                                <div>
                                    <input type="text" placeholder="Prénom de l'utilisateur" value="{{ old('prenom') }}" name="prenom" id="prenom" required minlength="2" maxlength="100"/>
                                </div>
                                <div>
                                    <input type="email" placeholder="Adresse courriel" value="{{ old('courriel') }}" id="courriel" name="courriel" required minlength="2" maxlength="200"/>
                                </div>
                                <div>
                                    <input class="modal-input phone" type="text" placeholder="Téléphone" name="telephone" {{ old('telephone') }} id="telephone" maxlength="15"/>
                                </div>
                                <select class="comboBoxForm" name="types_utilisateur_id"  id="types_utilisateur_id" required>
                                    <option class="comboBoxSelect" value="0">---Sélection---</option>
                                    @if(count($typeUtilisateurs))
                                        @foreach($typeUtilisateurs as $typeUtilisateur)
                                            <option class="comboBoxForm" value="{{$typeUtilisateur->id}}">{{$typeUtilisateur->type}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div>
                                    <input type="password" placeholder="Entrez le mot de passe" name="password" id="password" required minlength="8" maxlength="15" />
                                </div>
                                <div>
                                    <input type="password" placeholder="Entrez le mot de passe de nouveau" name="password_confirmation" id="password_confirmation" required minlength="8" maxlength="15"/>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input fb_chk-box" type="checkbox" name="is_admin" id="is_admin">
                                    <label class="form-check-label" for="is_admin">
                                        L'utilisateur est un administrateur
                                    </label>
                                </div>
                                <button type="submit">Créer l'utilisateur</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
@endsection
