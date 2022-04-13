@extends('layouts.app')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <body class="sub_page">
    <section class="login_section layout_padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="login_form">
                        <h5>
                            Modifier une disponibilité
                        </h5>
                        @foreach($disponibilites as $disponibilite)
                            <form id="form_disp" method="post" action="{{ route('disponibilites.update', [$disponibilite->id]) }}">
                                @csrf
                                @method('PATCH')
                                <label class="form-check-label fb_left" for="endroit_id">Lieu</label>
                                <select class="comboBoxForm" name="endroit_id"  id="endroit_id" required>
                                    @if(count($endroits))
                                        @foreach($endroits as $endroit)
                                            <option class="comboBoxSelect" value="{{$endroit->id}}"  @if($endroit->id == $disponibilite->endroit_id) selected @endif >{{$endroit->adresse}} {{$endroit->lieu}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <label class="form-check-label fb_left" for="utilisateur_id">Personne présente</label>
                                <select class="comboBoxForm" name="utilisateur_id"  id="utilisateur_id" required>
                                    @if(count($utilisateurs))
                                        @foreach($utilisateurs as $utilisateur)
                                            <option class="comboBoxSelect" value="{{$utilisateur->id}}" @if($utilisateur->id == $disponibilite->utilisateur_id) selected @endif>{{$utilisateur->prenom}} {{$utilisateur->nom}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div>
                                    <label class="form-check-label fb_left" for="debut">Date et heure de début</label>
                                    <input type="datetime-local" min="2020-01-01" name="debut" id="debut" value="{{$disponibilite->debut}}" required/>
                                </div>
                                <div>
                                    <label class="form-check-label fb_left" for="fin">Date et heure de fin</label>
                                    <input type="datetime-local" min="2020-01-01" name="fin" id="fin" value="{{$disponibilite->fin}}" required/>
                                </div>
                                <button type="submit">Modifier la disponibilité</button>
                            </form>
                            <form id="form_disp" method="post" action="{{ route('disponibilites.destroy', [$disponibilite->id]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Supprimer la disponibilité</button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    </body>
@endsection

