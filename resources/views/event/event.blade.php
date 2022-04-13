@extends('layouts.app')

@section('script')
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('js/jquery-input-mask-phone-number.min.js') }}"></script>
<script src="{{ asset('js/inscription.js') }}"></script>
@endsection

@section('content')
<!-- course section -->


<section class="course_section layout_padding">
  <div class="side_img">
    <img src="images/side-img.png" alt="" />
  </div>
  <div class="container">
    <div class="heading_container">
      <h3>
        Évènements
      </h3>
      <p>
        Évènements à venir pour stimuler vos méninges
      </p>

      @auth
      <a href="{{ URL::route('create_event') }}" class="btn btn-info">Ajouter un évènements</a>
      @endauth

    </div>
    <div class="container">


      <div class="container-fluid mt-8">
        <div class="row justify-content-center">


          @foreach($evenements->sortBy('date_heure') as $evenement)
          @if ($evenement->date_heure > Carbon\Carbon::now())
          <div class="col-auto mb-3">
            <div class="card">
              <div class="card-header">
                @auth
                      @if(Session::get('admin') == 1)
                        <a href="{{ URL::route('evenement.destroy', [$evenement->id]) }}"><i class="supprimer float-right bi bi-dash-circle-fill"></i></a>
                        <a href="{{ URL::route('evenement_edit', [$evenement->id]) }}"><i class="float-right bi bi-pencil-fill"></i></a>
                      @endif
                      <a href="{{ URL::route('participant.index', [$evenement->id]) }}"><i class="float-right bi bi-card-list"></i></a>
                @endauth
                <h5>{{ $evenement->titre }}</h5>
              </div>
              <img src="{{ asset('images_evenement') }}/{{ $evenement->lien_image }}" class="img img-card" alt="...">
              <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">{{ \Carbon\Carbon::parse($evenement->date_heure)->locale('fr')->format('j')}} {{ \Carbon\Carbon::parse($evenement->date_heure)->locale('fr')->monthName}}</h6>
                <p id="description" class="card-text limit">{{ $evenement->description }}</p>
              </div>
              @foreach($inscriptions->where('id', $evenement->id) as $inscription)
              <h1 href="" class="information" data-titre="{{ $evenement->titre }}" data-descrip="{{ $evenement->description }}" data-place="{{ $inscription->place_restante }}" data-adresse="{{ $evenement->adresse }}" data-date="{{ \Carbon\Carbon::parse($evenement->date_heure)->locale('fr')->format('H:i')}}" data-duree="{{ ($evenement->duree) }}" data-lieu="{{ $evenement->lieu }}" data-type="{{ $evenement->type }}">En savoir plus</h1>
              @endforeach
              <div class="card-footer">
                <a class="btn btn-participation btn-sm participer" data-titre="{{ $evenement->titre }}" data-id="{{ $evenement->id }}">Participer</a>
              </div>
            </div>
          </div>
          @endif

          @auth
          @if ($evenement->date_heure <= Carbon\Carbon::now()) <div class="col-auto mb-3">
            <div class="card">
              <div class="card-header">
                  @if(Session::get('admin') == 1)
                    <a href="{{ URL::route('evenement.destroy', [$evenement->id]) }}"><i class="supprimer float-right bi bi-dash-circle-fill"></i></a>
                  @endif
                  <a href="{{ URL::route('participant.index', [$evenement->id]) }}"><i class="float-right bi bi-card-list"></i></a>
                <h5>{{ $evenement->titre }}</h5>
              </div>
              <img src="{{ asset('images_evenement') }}/{{ $evenement->lien_image }}" class="img img-card" alt="...">
              <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">{{ \Carbon\Carbon::parse($evenement->date_heure)->locale('fr')->format('j')}} {{ \Carbon\Carbon::parse($evenement->date_heure)->locale('fr')->monthName}}</h6>
                <p id="description" class="card-text limit">{{ $evenement->description }}</p>
              </div>
              @foreach($inscriptions->where('id', $evenement->id) as $inscription)
              <h1 href="" class="information" data-titre="{{ $evenement->titre }}" data-descrip="{{ $evenement->description }}" data-place="{{ $inscription->place_restante }}" data-adresse="{{ $evenement->adresse }}" data-date="{{ \Carbon\Carbon::parse($evenement->date_heure)->locale('fr')->format('H:i')}}" data-duree="{{ ($evenement->duree) }}" data-lieu="{{ $evenement->lieu }}" data-type="{{ $evenement->type }}">En savoir plus</h1>
              @endforeach
              <div class="card-footer">
                <a class="btn btn-participation btn-sm participer disabled" data-titre="{{ $evenement->titre }}" data-id="{{ $evenement->id }}">Participer</a>
              </div>
            </div>
        </div>

        @endif
        @endauth

        @endforeach



      </div>
    </div>

  </div>



  </div>

  <div id="formPush" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">S'inscrire</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form" action="{{ URL::route('participant.store') }}" method="POST">
            @csrf
            <input type="hidden" id="id" name="id" value="" readonly>
            <input class="modal-input" type="text" name="evenement" id="titre" readonly>
            <br>
            <input class="modal-input" type="text" name="nom" id="nom" placeholder="Nom" required>
            <br>
            <input class="modal-input" type="text" name="prenom" id="prenom" placeholder="Prenom" required>
            <br>
            <input class="modal-input phone" type="text" name="telephone" id="telephone" placeholder="# Téléphone">
            <br>
            <input class="modal-input" type="text" name="courriel" id="courriel" placeholder="Adresse courriel" required>

            <div class="modal-footer">
              <button type="submit" class="btn btn-form btn-primary" data-dismiss="" id="submit_inscript">Participer</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
</section>





<!-- end course section -->
@endsection
