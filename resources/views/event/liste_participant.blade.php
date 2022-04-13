@extends('layouts.app')

@section('script')
<script type="text/javascript" src="{{ asset('js/evenement.js') }}"></script>
@endsection

@section('content')
<!-- course section -->

<section class="course_section layout_padding">
  <div class="heading_container">
    <h3>
      Participant - {{ $evenements->titre }}
    </h3>
  </div>

  <div class="container">
    <input class="form-control recherche" type="text" id="search" placeholder="Faire une recherche">
    <br>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Nom</th>
          <th scope="col">Prenom</th>
          <th scope="col"># tel</th>
          <th scope="col">Courriel</th>
          <th class="center-cell" scope="col">Annuler inscription</th>
        </tr>
      </thead>
      <tbody id="table">

        @foreach($participants as $participant)
        <tr>
          <td>{{ $participant->nom }}</td>
          <td>{{ $participant->prenom }}</td>
          <td>{{ $participant->telephone }}</td>
          <td>{{ $participant->courriel }}</td>
          <td class="center-cell"><a href="{{ URL::route('participant.delete', [$participant->token]) }}"><i class="supprimer-inscription bi bi-person-x-fill"></i></a></td>
        </tr>
        @endforeach

      </tbody>
    </table>
  </div>
</section>

<!-- end course section -->
@endsection