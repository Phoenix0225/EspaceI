@extends('layouts.app')
@section('content')
<!-- event section -->
<section class="event_section layout_padding">
  <div class="container">
    <div class="heading_container">
      <h3>
        Rendez-vous
      </h3>
      <p>
        Afficher un rendez-vous.
      </p>
    </div>
    @csrf
      <!-- Sélection heures -->
    <div>
        <div class="form-group">
            <label>Nom:</label>
            <input type="text" class="form-control" value="{{$rdv->nom_client}}" disabled>
        </div>
        <div class="form-group">
            <label>Date et heure:</label>
            @php
                $start = strtotime(date('Y-m-d',strtotime($rdv->Disponibilite->debut)).' '.$rdv->heure_debut);
                $end = strtotime('+'.$rdv->duree_avg.' minutes',$start);
            @endphp
        <input type="text" class="form-control" value="{{date('Y-m-d',strtotime($rdv->Disponibilite->debut))}} de {{date('H:i',$start)}} à {{date('H:i',$end)}}" disabled>
        </div>
        <div class="form-group">
            <label>Courriel:</label>
            <input type="text" class="form-control" value="{{$rdv->courriel}}" disabled>
        </div>
        <div class="form-group">
            <label>Téléphone:</label>
            <input type="text" class="form-control" value="@if($rdv->telephone == null) N/D @else {{$rdv->telephone}} @endif" disabled>
        </div>
        <div class="form-group">
            <label>Description:</label>
            <input type="text" class="form-control" value="@if($rdv->description_rdv == null) N/D @else {{$rdv->description_rdv}} @endif" disabled>
        </div>
        <form method="POST" action="{{route('appointment.delete',$rdv->id)}}" id="delete">
            @csrf
            <button type="submit" class="btn btn-danger">Supprimer</button>
        </form>
    </div>
</section>

@endsection

@section('script')
@endsection
