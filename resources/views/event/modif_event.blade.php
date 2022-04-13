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
                        Modification d'un évènement
                    </h5>
                    <form action="{{route('evenement.update',$evenement->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <input type="text" placeholder="Titre" value="{{$evenement->titre}}" name="titre" id="titre" required />
                        </div>
                        <div>
                            <textarea class="form-control" rows="4" name="description" placeholder="Description" id="description" required>{{$evenement->description}}</textarea>
                        </div>
                        <div class="d-flex stretchFlex">
                            <select class="comboBoxForm" name="endroit_id" id="endroit_id" value="{{$evenement->endroit_id}}">
                                @if(count($endroits))
                                @foreach($endroits as $endroit)
                                @if($endroit->id == $evenement->endroit_id)
                                <option class="comboBoxSelect" value="{{$endroit->id}}" selected="selected">{{$endroit->adresse}}, {{$endroit->lieu}}</option>
                                @else
                                <option class="comboBoxSelect" value="{{$endroit->id}}">{{$endroit->adresse}}, {{$endroit->lieu}}</option>
                                @endif
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
                            <input type="text" placeholder="Url Zoom(Facultatif)" name="lien_zoom" id="lien_zoom" value="{{$evenement->url_zoom}}" />
                        </div>
                        <div>
                            <input type="datetime-local" name="date_heure" id="date_heure" min="2017-06-01T08:00" value="{{ \Carbon\Carbon::parse($evenement->date_heure)->toDateTimeLocalString()}}" max="9999-12-31T23:59" required />
                        </div>
                        <div>
                            <input type="number" placeholder="Durée en minute" name="duree" id="duree" value="{{$evenement->duree}}" min="1" required/>
                        </div>
                        <div>
                            <input type="number" placeholder="Nombre de place" name="nb_places" id="nb_places" value="{{$evenement->nb_places}}" min="1" />
                        </div>
                        <div class="d-flex stretchFlex">
                            <select class="comboBoxForm" name="types_evenement_id" id="types_evenement_id" value="{{$evenement->type}}" required>
                                @if(count($typeEvenements))
                                @foreach($typeEvenements as $typeEvenement)
                                @if($typeEvenement->id == $evenement->types_evenement_id)
                                <option class="comboBoxSelect" value="{{$typeEvenement->id}}" selected="selected">{{$typeEvenement->type}}</option>
                                @else
                                <option class="comboBoxSelect" value="{{$typeEvenement->id}}">{{$typeEvenement->type}}</option>
                                @endif
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

                        <button type="submit">Modifier l'évènement</button>
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