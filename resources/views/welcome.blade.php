@extends('layouts.app')

@section('carousel')
  <section class=" slider_section position-relative">
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <div class="container">
            <div class="row">
              <div class="col">
                <div class="detail-box">
                  <div>
                    <h1>
                      Prise de rendez-vous
                    </h1>
                    <a href="{{ route('appointment.category') }}">
                      En savoir plus
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="carousel-item ">
          <div class="container">
            <div class="row">
              <div class="col">
                <div class="detail-box">
                  <div>
                    <h1>
                      Évènements
                    </h1>
                    <a href="{{ route('evenement') }}">
                      En savoir plus
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="carousel-item ">
          <div class="container">
            <div class="row">
              <div class="col">
                <div class="detail-box">
                  <div>
                    <h1>
                      Sousmettre un billet
                    </h1>
                    <a href="{{ route('billets.create') }}">
                      En savoir plus
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('content')
  <!-- about section -->
  <section id="a-propos" class="about_section layout_padding">
    <div class="side_img">
      <img src="images/side-img.png" alt="" />
    </div>
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="img_container">
            <div class="img-box b1">
              <img src="images/a-1.jpg" alt="" />
            </div>
            <div class="img-box b2">
              <img src="images/a-2.jpg" alt="" />
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="detail-box">
            <div class="heading_container">
              <h3>
                À propos du service
              </h3>
              <p>
                  {{$parametre->txt_a_propos_accueil}}
              </p>
              <a href="{{ route('faq') }}">
                En savoir plus
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end about section -->

  <!-- event section -->
  <section id="evenements" class="event_section layout_padding">
    <div class="container">
      <div class="heading_container">
        <h3>
          Évènements
        </h3>
        <p>
          Évènements à venir pour stimuler vos méninges
        </p>
      </div>
      <div class="event_container">
          @if(count($evenements))
              @foreach($evenements as $evenement)
                  <div class="box">
                      <div class="img-box">
                          <img src="{{ asset('images_evenement') }}/{{ $evenement->lien_image }}" alt="" />
                      </div>
                      <div class="detail-box">
                          <h4>
                              {{$evenement->titre}}
                          </h4>
                          <h6>
                              {{$evenement->heure}} {{$evenement->adresse}} {{$evenement->lieu}}
                          </h6>
                      </div>
                      <div class="date-box">
                          <h3>
                              <span>
                                {{ \Carbon\Carbon::parse($evenement->date_heure)->locale('fr')->format('j')}}
                              </span>
                              {{ \Carbon\Carbon::parse($evenement->date_heure)->locale('fr')->monthName}}
                          </h3>
                      </div>
                  </div>
              @endforeach
          @endif
      </div>
      <div class="btn-box">
        <a href="{{ route('evenement') }}">
          En savoir plus
        </a>
      </div>
    </div>
  </section>
  <!-- end event section -->

  <!-- client section -->
  <section class="client_section layout_padding-bottom">
    <div class="container">
      <div class="heading_container">
        <h3>
          Avis des spécialistes
        </h3>
        <p>
          Laisser vous tenter par ce que les autres en disent
        </p>
      </div>
      <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="box">
              <div class="img-box img-commentaire">
                <img src="images/bill_gates.png" alt="" />
              </div>
              <div class="detail-box">
                <h5>
                  Bill Gates
                </h5>
                <p>
                  Espace I est le meilleur service informatique pour régler vos problèmes reliés aux ordinateurs avec un système Windows.
                </p>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="box">
              <div class="img-box img-commentaire">
                <img src="images/zucc.png" alt="" />
              </div>
              <div class="detail-box">
                <h5>
                    Mark Zuckerberg
                </h5>
                <p>
                  Mark Zuckerberg nous fait confiance avec tout ce qui est Meta!
                </p>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="box">
              <div class="img-box img-commentaire">
                <img src="images/francois.png" alt="" />
              </div>
              <div class="detail-box">
                <h5>
                    François Jacob
                </h5>
                <p>
                    François nous trouve très bon en informatique!
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="btn-box">
          <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
    </div>
  </section>
  <!-- end client section -->

  <!-- contact section -->
  <section id="contact" class="contact_section ">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="detail-box">
            <div class="heading_container">
              <h3>
                Contactez-Nous
              </h3>
              <p>
                Vous avez besoin d'informations ou de conseils? Écrivez-nous et il nous fera un grand plaisir de vous répondre.
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="contact-form">
            <h5>
              Entrez en contact avec nous
            </h5>
            @if ($errors->any())
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
            @endif
            <form method="POST" action="{{route('email.contact')}}">
                @csrf
                <div>
                  <input type="text" placeholder="Nom Complet" name="name" minlength="2" maxlength="100" required>
                </div>
                <div>
                  <input type="text" placeholder="Numéro de téléphone" name="telephone" maxlength="15">
                </div>
                <div>
                  <input type="email" placeholder="Adresse courriel" name="email" minlength="2" maxlength="100">
                </div>
                <div>
                  <input type="text" placeholder="Message" class="input_message"  name="message" required min="25">
                </div>
              <div class="d-flex justify-content-center">
                <button type="submit" class="btn_on-hover">
                  Envoyer
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end contact section -->

@endsection
