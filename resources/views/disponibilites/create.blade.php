@extends('layouts.app')

@section('script')
    <script type="text/javascript" src="{{ asset('js/disponibilites.js') }}"></script>
@endsection

@section('content')
    <body class="sub_page">
    <section class="login_section layout_padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="login_form">
                        <h5>
                            Nouvelle disponibilité
                        </h5>
                        <form id="form_disp" method="post" action="{{route('disponibilites.store')}}">
                            @csrf
                            <label class="form-check-label fb_left" for="endroit_id">Lieu</label>
                            <select class="comboBoxForm" name="endroit_id"  id="endroit_id" required>
                                @if(count($endroits))
                                    @foreach($endroits as $endroit)
                                        <option class="comboBoxSelect" value="{{$endroit->id}}" {{ old('endroit_id') == $endroit ? "selected" : "" }}>{{$endroit->adresse}} {{$endroit->lieu}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label class="form-check-label fb_left" for="utilisateur_id">Personne présente</label>
                            <select class="comboBoxForm" name="utilisateur_id"  id="utilisateur_id" required>
                                @if(count($utilisateurs))
                                    @foreach($utilisateurs as $utilisateur)
                                        <option class="comboBoxSelect" value="{{$utilisateur->id}}" {{ old('utilisateur_id') == $utilisateur ? "selected" : "" }}>{{$utilisateur->prenom}} {{$utilisateur->nom}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div>
                                <label class="form-check-label fb_left" for="debut">Date et heure de début</label>
                                <input type="datetime-local" min="2020-01-01T00:00:00" name="debut" id="debut" value="{{ old('debut') }}" required/>
                            </div>
                            <div>
                                <label class="form-check-label fb_left" for="fin">Date et heure de fin</label>
                                <input type="datetime-local" min="2020-01-01T00:00:00" max="2025-01-01T00:00:00" name="fin" id="fin" value="{{ old('fin') }}" required/>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label" for="is_recur">
                                    Récurent
                                    <input class="form-check-input fb_chk-box" type="checkbox" name="is_recur" id="is_recur">
                                </label>
                                <div></div>
                            </div>
                            <div>
                                <label class="form-check-label fb_left" for="date_max">Se répette jusqu'au</label>
                                <input type="date"  name="date_max" id="date_max" disabled="disabled"/>
                            </div>
                            <button type="submit">Créer la disponibilité</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </body>
@endsection
