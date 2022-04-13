@extends('layouts.app')

@section('style')
    <link href="{{ asset('css/dashboard.css')}}" rel="stylesheet">
@endsection

@section('content')
    <section class="user_section layout_padding">
        <div class="container">
            <div class="heading_container">
                <h3>
                    Mon horaire
                </h3>
            </div>
            <div class="container py-7">
                <div class="row">
                    @if(count($disponibilites))
                        @foreach($disponibilites as $disponibilite)
                            <div class="col-lg-4 mb-3" id="{{$disponibilite->id_disp}}">
                                <h5 class="mt-0 mb-3 text-dark op-8 font-weight-bold">
                                    {{$disponibilite->DescHoraire}}
                                </h5>
                                <ul class="list-timeline list-timeline-primary">
                                    @if(count($rendez_vous))
                                        @foreach($rendez_vous as $rendezvous)
                                            @if($disponibilite->id_disp == $rendezvous->id_disp)
                                                <a href="{{ route('appointment.show', $rendezvous->id_rdv) }}">
                                                    <li class="list-timeline-item p-0 pb-3 pb-lg-4 d-flex flex-wrap flex-column" data-toggle="collapse" data-target="#{{$rendezvous->id_rdv}}">
                                                        <p class="my-0 text-dark flex-fw text-sm text-uppercase"><span class="text-inverse op-8">{{$rendezvous->heure_debut}}</span> - {{$rendezvous->nom_client}}</p>
                                                        <p class="my-0 flex-fw text-uppercase text-xs text-dark op-8" id="{{$rendezvous->id_rdv}}"> {{$rendezvous->probleme}} </p>
                                                    </li>
                                                </a>
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <br/><br/>
        <div class="container">
            <div class="heading_container">
                <h3>
                    Mes billets
                </h3>
            </div>
            <div class="event_container user_container">
                <table class="table">
                    <thead table-light>
                        <tr>
                            <th scope="col">Statut</th>
                            <th scope="col">Titre</th>
                            <th scope="col">Client</th>
                            <th scope="col">Priorité</th>
                            <th scope="col">Type</th>
                            <th scope="col">Créé le</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($billets))
                            @foreach($billets as $billet)
                                <tr>
                                    <td>{{ $billet->statut }}</td>
                                    <td><a href="{{ route('billets.edit', $billet->idBillet) }}">{{ $billet->titre }}</a></td>
                                    <td>{{ $billet->nom_client }}</td>
                                    <td>{{ $billet->priorite }}</td>
                                    <td>{{ $billet->categorie }}</td>
                                    <td>{{ $billet->DateCreation }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
