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
        Prendre un rendez-vous celon l'horaire disponible.
      </p>
    </div>
    <form>
      <div class="container">
        <p>Choissir un type de rendez-vous<span class="text-danger">*</span>:</p>
        <div class="row justify-content-md-center">
          @foreach($problemes as $probleme)
          <div class="col-12 col-lg-3 category">
              <h3>{{ $probleme->probleme }}</h3>
              <p>{{ $probleme->duree }} Min</p>
              <a href="{{route("appointment.date",$probleme->id)}}"><button class="btn btn-primary selectCategory" type="button">Prendre RDV</button></a>
          </div>
          @endforeach
        </div>
      </div>
    </form>
  </div>
</section>
@endsection