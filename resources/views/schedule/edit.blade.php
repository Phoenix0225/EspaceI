@extends('layouts.app')
@section('content')
<!-- event section -->
<section class="event_section layout_padding">
  <div class="container">
    <!-- Header -->
    <div class="heading_container">
        <h3>
            Horaire du local SB0126
        </h3>
        <p>
            Modifier et visualer l'horaire d'un local.
        </p>
    </div>
    <form>

    <!-- Calendrier créer avec FullCalendar.io -->
    <div id='calendar'></div>
    <!-- Heure d'ouverture et de fermeture -->
    <div class="row">
      <!-- Heure ouverture -->
      <div class="form-group col-md-6 col-12">
        <label for="startHours">Heure d'ouverture du 7 septembre 2021<span class="text-danger">*</span>:</label>
        <input type="time" class="form-control" id="startHours" name="startHours" value="08:00">
      </div>
      <!-- Heure fermeture -->
      <div class="form-group col-md-6 col-12">
        <label for="endHours">Heure de fermeture du 7 septembre 2021<span class="text-danger">*</span>:</label>
        <input type="time" class="form-control" id="endHours" name="endHours" value="17:45">
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Modifier</button>
  </form>
  </div>
</section>
@endsection
