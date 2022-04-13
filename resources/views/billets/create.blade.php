@extends('layouts.app')

@section('script')
    <script type="text/javascript" src="{{ asset('js/billets.js') }}"></script>
    <script src="{{ asset('js/jquery-input-mask-phone-number.min.js') }}"></script>
@endsection

@section('style')
    <link href="{{ asset('css/cedric.css') }}" rel="stylesheet">
@endsection

@section('content')

    <section id="create-ticket-tile" class="login_section layout_padding create-tile">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="login_form">
                        <h5>
                            Nouveau billet
                        </h5>
                        <form action="{{ route('billets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="previousURL" value="{{ url()->previous() == url()->current() ? old('previousURL') : url()->previous() }}">
                            <div class="form-div">
                                <input type="text" class="form-control" name="nom_client" value="{{ old('nom_client') }}" placeholder="Nom complet" id="nom_client" required>
                            </div>
                            <div class="form-div">
                                <input type="text" class="form-control" name="courriel" value="{{ old('courriel') }}" placeholder="Courriel du cégep de Trois-Rivières" id="courriel" required>
                            </div>
                            <div class="form-div">
                                <input type="tel" class="form-control" maxlength="12" name="telephone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="{{ old('telephone') }}" placeholder="Numéro de téléphone" id="telephone">
                            </div>
                            <div class="form-div">
                                <select class="comboBoxForm m-0" name="billet_categorie_id" id="billet_categorie_id">
                                    <option class="comboBoxSelect" value="0" disabled {{ old('billet_categorie_id') ? '' : 'selected' }}>Type du billet</option>
                                    @if(count($billetCategories))
                                        @foreach($billetCategories as $billetCategorie)
                                            @if($billetCategorie->id != 1)
                                                <option class="comboBoxSelect" value="{{ $billetCategorie->id }}" {{ strval($billetCategorie->id) === old('billet_categorie_id') ? 'selected' : '' }}>{{ $billetCategorie->categorie }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-div">
                                <input type="text" class="form-control" name="titre" value="{{ old('titre') }}" placeholder="Sujet" id="titre" required>
                            </div>
                            <div class="form-div">
                                <textarea class="form-control" rows="3" name="description_billet" placeholder="Description du billet" id="description_billet" required>{{ old('description_billet') }}</textarea>
                            </div>
                            <button type="submit">Soumettre le billet</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
