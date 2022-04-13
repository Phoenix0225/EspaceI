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

  <!-- course section -->
  <section class="course_section layout_padding">
    <div class="side_img">
      <img src="images/side-img.png" alt="" />
    </div>
    <div class="container">
      <div class="heading_container">
        <h3>
          FOIRE AUX QUESTIONS
        </h3>
        <p>
          Les réponses à toutes vos questions sur Espace i!
        </p>
          @auth
              @if(Session::get('admin') == 1)
                <a href="{{ route('faq.create') }}" class="btn btn-outline-info">Nouvelle question</a>
              @endif
          @endauth
      </div>
      @if(count($faqGroupes) && count($faqs))
        @foreach($faqGroupes as $faqGroupe)
          <div class="container delete-parent">
            <div class="row row-faq">
              <div class="col-1"></div>
              <div class="col-10">
                <div class="card w-100" style="width: 18rem;">
                  <div class="card-header text-center faq-header">
                    {{ $faqGroupe->groupe }}
                      @auth
                        @if(Session::get('admin') == 1)
                            <i class="float-right bi bi-dash-circle-fill supprimer-faq-groupe" data-id="{{ $faqGroupe->id }}" data-token="{{ csrf_token() }}"></i>
                        @endif
                      @endauth
                  </div>
                  @foreach ($faqs as $faq)
                    @if($faqGroupe->id == $faq->faq_groupe_id)
                      <ul class="list-group list-group-flush delete-parent">
                        <div class="question">
                          <li class="list-group-item text-question">
                            <div class="row">
                              <div class="col-11 pr-0">
                                <div class="d-inline">
                                  {{ $faq->question }}
                                </div>
                              </div>
                              <div class="col-1 p-0">
                                <div class="float-right pr-2">
                                    <i class="bi bi-caret-down-fill"></i>
                                    @auth
                                        @if(Session::get('admin') == 1)
                                            <i class="bi bi-dash-circle-fill supprimer-faq" data-id="{{ $faq->id }}" data-token="{{ csrf_token() }}"></i>
                                        @endif
                                    @endauth
                                </div>
                              </div>
                            </div>
                          </li>
                          <li class="list-group-item reponse">
                            <div>
                              {{ $faq->reponse }}
                            </div>
                          </li>
                        </div>
                      </ul>
                    @endif
                  @endforeach
                </div>
              </div>
              <div class="col-1"></div>
            </div>
          </div>
        @endforeach
      @endif
    </div>
  </section>
  <!-- end course section -->
@endsection
