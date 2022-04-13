@extends('layouts.app')

@section('script')
    <script type="text/javascript" src="{{ asset('js/faq.js') }}"></script>
@endsection

@section('style')
    <link href="{{ asset('css/cedric.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
@endsection

@section('content')

    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="7500">
        <div class="toast-header">
            <i class="icon-toast bi mr-1 text-white"></i>
            <strong class="titre-toast mr-auto text-white"></strong>
        </div>
        <div class="toast-body text-white" id="message-toaster"></div>
    </div>

    <section id="create_faq_tile" class="login_section layout_padding create-tile">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="login_form">
                        <a href="{{ route('faq') }}"><i class="bi bi-arrow-left text-white"></i></a>
                        <h5 class="d-inline">
                            Nouvelle entrée dans la FAQ
                        </h5>
                        <form>
                            <div class="d-flex stretchFlex">
                                <select class="comboBoxForm" name="faq_groupe_id" id="faq_groupe_id">
                                    <option class="comboBoxSelect" value="0" disabled selected>Catégorie de la question</option>
                                    @if(count($faqGroupes))
                                        @foreach($faqGroupes as $faqGroupe)
                                            <option class="comboBoxSelect" value="{{$faqGroupe->id}}">{{$faqGroupe->groupe}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div id="nouveauType">
                                    <input type="text" name="nomGroupeFaq" placeholder="Nouvelle catégorie" id="nouvelleCategorie"/>
                                </div>
                                <button type="button" id="ajout-groupe-faq" class="btn btn-dark" title="Nouvelle catégorie">+</button>
                            </div>
                            <div class="form-div">
                                <textarea class="form-control" rows="3" name="question" placeholder="Question" id="question" required maxlength="150">{{ old('question') }}</textarea>
                            </div>
                            <div class="form-div">
                                <textarea class="form-control" rows="5" name="reponse" placeholder="Réponse" id="reponse" required>{{ old('reponse') }}</textarea>
                            </div>
                            <button type="button" id="create-faq" data-token="{{ csrf_token() }}">Créer la question</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
