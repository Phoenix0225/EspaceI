@extends('layouts.app')

@section('script')
<script type="text/javascript" src="{{ asset('js/evenement.js') }}"></script>
@endsection

@section('content')



<!-- course section -->
<section class="login_section layout_padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="login_form">
                    <h5>
                        Nouvel évènement
                    </h5>
                    <form action="{{ URL::route('evenement.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <input class="form-control" type="text" placeholder="Titre" name="titre" id="titre" value ="{{ old('titre') }}" required />
                        </div>
                        <div>
                            <textarea class="form-control" rows="4" name="description" placeholder="Description" id="description" required>{{ old('description') }}</textarea>
                        </div>
                        <div class="d-flex stretchFlex">
                            <select class="comboBoxForm" name="endroit_id" id="endroit_id">
                                <option class="comboBoxSelect" value="0" disabled {{ old('endroit_id') ? '' : 'selected' }}>Adresse</option>
                                @if(count($endroits))
                                @foreach($endroits as $endroit)
                                <option class="comboBoxSelect" value="{{$endroit->id}}">{{$endroit->adresse}}, {{$endroit->lieu}}</option>
                                @endforeach
                                @endif
                            </select>
                            <div id="ajoutEndroit">
                                <input type="text" id="nouvelAdresse" name="adresse" class="form-control" placeholder="Adresse">
                                <input type="text" id="nouvelEndroit" name="lieu" class="form-control" placeholder="Endroit">
                            </div>

                            <button type="button" id="btnAjoutEndroit" class="btn btn-dark" title="Ajout endroit" style="vertical-align: middle;">+</button>
                        </div>
                        <div>
                            <input type="text" placeholder="Url Zoom(Facultatif)" name="lien_zoom" id="lien_zoom" value="{{ old('lien_zoom') }}" />
                        </div>
                        <div>
                            <input type="datetime-local" name="date_heure" id="date_heure" min="2017-06-01T08:00" max="9999-12-31T23:59" value="{{ old('date_heure') }}" required/>
                        </div>
                        <div>
                            <input type="number" placeholder="Durée en minute" name="duree" id="duree" min="1" value="{{ old('duree') }}" required />
                        </div>
                        <div>
                            <input type="number" placeholder="Nombre de place (laisser vide si illimité)" name="nb_places" id="nb_places" min="1" value="{{ old('nb_places') }}" />
                        </div>
                        <div class="d-flex stretchFlex">
                            <select class="comboBoxForm" name="types_evenement_id" id="types_evenement_id" required>
                                <option class="comboBoxSelect" value="0" disabled disabled {{ old('types_evenement_id') ? '' : 'selected' }}>Type d'évènement</option>
                                @if(count($typeEvenements))
                                @foreach($typeEvenements as $typeEvenement)
                                <option class="comboBoxSelect" value="{{$typeEvenement->id}}">{{$typeEvenement->type}}</option>
                                @endforeach
                                @endif
                            </select>
                            <div id="ajoutTypeEvent">
                                <input type="text" id="nouvelEvent" name="type_description" class="form-control" placeholder="Type d'évènement">
                            </div>
                            <button type="button" id="btnAjoutTypeEvent" class="btn btn-dark" title="Ajout type évènement" style="vertical-align: middle;">+</button>
                        </div>
                        <div class='parent inline-flex-parent padding-upload'>
                        <div class="child padding-text">
                            <label style="vertical-align: middle;" for="lien_img">Image: </label>
                        </div>
                        <div class="child"> 
                            <input  type="file" name="lien_img" id="lien_img" />
                        </div>
                        </div>

                        <button type="submit">Créer l'évènement</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end course section -->

@endsection

@section('scriptBas')
<script type="text/javascript" src="{{ asset('js/dateEvent.js') }}"></script>
@endsection