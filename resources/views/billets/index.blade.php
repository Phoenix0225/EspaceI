@extends('layouts.app')

@section('script')
    <script type="text/javascript" src="{{ asset('js/billets.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.js"></script>
@endsection

@section('style')
    <link type="text/css" rel="stylesheet" href="{{ asset('css/cedric.css') }}">
    <link type="text/css" rel="stylesheet" href="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.css"/>
@endsection

@section('content')

    <section class="event_section layout_padding pt-2 create-tile">
        <div class="pt-5 mr-5 ml-5">
            <div class="heading_container">
                <h3>
                    Billets
                </h3>
                <p>
                    Visualiser les billets d'Espace I
                </p>
            </div>
            <a href="{{ route('billets.create') }}" class="btn btn-outline-info mb-3">Nouveau billet</a>
            <div class="d-flex flex-row">
               <form action="{{ route('billets') }}" method="GET" class="w-100">
                   <div class="input-group mb-3 d-flex flex-wrap">
                       <div class="input-group-prepend">
                           <label class="input-group-text">Filtres</label>
                       </div>
                       <select class="custom-select option-billets" name="categorie">
                           <option selected disabled id="categorie-option" value="0">Type</option>
                           @foreach($billetCategories as $billetCategorie)
                                <option value="{{ $billetCategorie->id }}">{{ $billetCategorie->categorie }}</option>
                           @endforeach
                       </select>
                       <select class="custom-select option-billets" name="priorite">
                           <option selected disabled id="priorite-option" value="0">Priorité</option>
                           @foreach($priorites as $priorite)
                               <option value="{{ $priorite->id }}">{{ $priorite->priorite }}</option>
                           @endforeach
                       </select>
                       <select class="custom-select option-billets" name="statut">
                           <option selected disabled id="statut-option" value="0">Statut</option>
                           @foreach($statuts as $statut)
                               <option value="{{ $statut->id }}">{{ $statut->statut }}</option>
                           @endforeach
                       </select>
                       <div class="input-group-append">
                           <button type="button" class="btn btn-outline-secondary" id="reset">Réinitialiser</button>
                           <button type="submit" class="btn btn-outline-success">Appliquer</button>
                       </div>
                   </div>
               </form>
            </div>
            {{--     Menu des billets     --}}
            <table class="display" style="width:100%" id="billets-table">
                <thead>
                    <tr>
                        <th scope="col">Numéro</th>
                        <th scope="col">Type</th>
                        <th scope="col">Titre</th>
                        <th scope="col">Priorité</th>
                        <th scope="col">Par</th>
                        <th scope="col">Statut</th>
                        <th scope="col">Créer à</th>
                        <th scope="col">Modifié à</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($billets))
                        @foreach($billets as $billet)
                            <tr class="clickable-row" data-href="{{ route('billets.edit', $billet->id) }}">
                                <td>{{ $billet->id }}</td>
                                <td>{{ $billet->categorie }}</td>
                                <td>{{ $billet->titre }}</td>
                                <td>{{ $billet->priorite }}</td>
                                <td>{{ $billet->nom_client }}</td>
                                <td>{{ $billet->statut }}</td>
                                <td>{{ $billet->created_at }}</td>
                                <td>{{ $billet->updated_at }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            $('#billets-table').DataTable( {
                "language": {
                    url: "//cdn.datatables.net/plug-ins/1.11.3/i18n/fr_fr.json",
                },
                "order": [[ 0, "desc" ]]
            } );
        } );
    </script>

@endsection
