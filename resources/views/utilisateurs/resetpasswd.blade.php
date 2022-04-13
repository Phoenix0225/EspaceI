@extends('layouts.app')

@section('content')
    <body class="sub_page">
    <section class="login_section layout_padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="login_form">
                        <h5>
                            Modifier mon mot de passe
                        </h5>
                        <form method="post" action="{{route('utilisateurs.updatePasswd',$utilisateur->id)}}">
                            @csrf
                            @method('PATCH')
                            <div>
                                <p>{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</p>
                            </div>
                            <div>
                                <input type="password" placeholder="Entrez votre mot de passe actuel" name="cur_passwd" id="cur_passwd" minlength="8" maxlength="15" />
                            </div>
                            <div>
                                <input type="password" placeholder="Entrez le nouveau mot de passe" name="password" id="password" minlength="8" maxlength="15" />
                            </div>
                            <div>
                                <input type="password" placeholder="Confirmer le mot de passe" name="password_confirmation" id="password_confirmation" minlength="8" maxlength="15"/>
                            </div>
                            <button type="submit">Modifier le mot de passe</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </body>
@endsection
