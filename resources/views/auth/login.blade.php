@extends('layouts.app')

@section('content')

  <section id="login_tile" class="login_section layout_padding">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="login_form">
            @if(session('error'))
                    <div class="alert alert-danger">
                        {{session('error')}}
                    </div>
                @endif
            <h5>
              Connectez-vous
            </h5>
            <form method="post" action="{{route('connexion.login')}}">
              {{ csrf_field() }}
              <input type="hidden" value="{{ url()->previous() == url()->current() ? old('previousURL') : url()->previous() }}" name="redirect">
              <div>
              <input type="courriel" name="courriel" placeholder="Courriel" value="{{old('courriel')}}">
              </div>
              <div>
                <input type="password" name="password" placeholder="Mot de passe" />
              </div>
              <div class="form-check">
                <input id="chk-box-connexion" class="form-check-input" type="checkbox" name="remember">
                <label class="form-check-label" for="chk-box-connexion">
                  Rester connecté
                </label>
              </div>
              <button type="submit">Connecter</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection
