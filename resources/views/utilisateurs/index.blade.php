@extends('layouts.app')

@section('content')
    <!-- event section -->
    <section class="event_section user_section layout_padding">
        <div class="container">
            <div class="heading_container">
                <h3>
                    Gestion des utilisateurs
                </h3>
                <p>
                    <a href="{{URL::route('utilisateurs.create')}}"> Créer un nouvel utilisateur </a>
                </p>
            </div>
            <div class="event_container user_container">
                <table class="table">
                    <thead table-light>
                    <tr>
                        <th scope="col">Nom, Prénom</th>
                        <th scope="col">Courriel</th>
                        <th scope="col">Téléphone</th>
                        <th scope="col">Mot de passe</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($utilisateurs))
                        @foreach($utilisateurs as $utilisateur)
                            <tr>
                                @if($utilisateur->id == 1)
                                    <td>{{$utilisateur->nom}}, {{$utilisateur->prenom}}</td>
                                @else
                                    <td><a id="{{$utilisateur->id}}" href="{{route('utilisateurs.edit',$utilisateur->id)}}" >{{$utilisateur->nom}}, {{$utilisateur->prenom}}</a></td>
                                @endif
                                    <td>{{$utilisateur->courriel}}</td>
                                    <td>{{$utilisateur->telephone}}</td>
                                @if($utilisateur->id != 1)
                                    <td><a href="{{route('utilisateurs.resetLogin',$utilisateur->id)}}">Réinitialiser le mot de passe</a></td>
                                @endif
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
