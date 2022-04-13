@php
$routeName = Route::currentRouteName();
@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/fullcalendar.io/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('js/fullcalendar.io/fr-ca.js') }}"></script>


    @yield('script')

    <script type="text/javascript" src="{{ asset('js/louka.js') }}"></script>

    <link rel="icon" href="{{ asset('images/logo-without-text.png') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css.map') }}" rel="application/json">
    <link href="{{ asset('css/style.scss') }}" rel="stylesheet">
    <link href="{{ asset('css/louka.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jeremy.css') }}" rel="stylesheet">
    <link href="{{ asset('css/francis.css')}}" rel="stylesheet">
    <link href="{{ asset('css/cedric.css')}}" rel="stylesheet">
    <link href="{{ asset('css/fullcalendar.min.css') }}" rel="styleshet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    @yield('style')
</head>

<body class="{{ $routeName === 'acceuil' ? '' : 'sub_page' }}">
    <noscript>
        <div class="alert alert-danger">
            Votre JavaScript est désactivé. Veuillez l'activer pour avoir un accès complet aux fonctionnalités de notre application.
        </div>
    </noscript>
    <div id="top" class="hero_area">
        <header class="header_section">
            <div class="container-fluid pr-lg-0">
                <nav class="navbar navbar-expand-lg custom_nav-container ">
                    <a class="navbar-brand" href="{{ route('acceuil') }}">
                        <img id="logo" src="{{ asset('images/logo_blanc.png') }}" alt="Logo Esapace I" title="Logo Esapace I" />
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse ml-auto" id="navbarSupportedContent">
                        <ul class="navbar-nav  ml-auto">
                            <li class="nav-item {{ $routeName === 'acceuil' ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('acceuil') }}">Accueil <span class="sr-only">(current)</span></a>
                            </li>
                            @auth
                            <li class="nav-item {{ $routeName === 'dashboard.index' ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboard.index', Session::get('utilisateur')) }}"> Tableau de bord </a>
                            </li>
                            @endauth
                            <li class="nav-item {{ Str::startsWith($routeName, 'appointment') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('appointment.category') }}"> Rendez-vous </a>
                            </li>
                            <li class="nav-item {{ Str::startsWith($routeName, 'evenement') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('evenement') }}"> Événements </a>
                            </li>
                            <li class="nav-item {{ Str::startsWith($routeName, 'faq') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('faq') }}"> FAQ </a>
                            </li>
                            <li class="nav-item {{ Str::startsWith($routeName, 'tutoriel') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('tutoriels') }}"> Tutoriels </a>
                            </li>
                            <li class="nav-item {{ Str::startsWith($routeName, 'billet') ? 'active' : '' }}">
                                <a class="nav-link" href="@auth {{ route('billets') }} @endauth @guest {{ route('billets.create') }} @endguest"> Billets </a>
                            </li>
                            @auth
                            @if(Session::get('admin') == 1)
                            <li class="nav-item p-0 btn-nav {{ Str::startsWith($routeName, 'parametre') ? 'active' : '' }}">
                                <a class="nav-link pb-2" id="settings-btn-nav" href="{{ route('parametres') }}"><img src="{{ asset('images/settings.png') }}" class="btn-nav-img"></a>
                            </li>
                            @endif
                            <li id="nav-disconnect" class="nav-item {{ $routeName === 'connexion' ? 'active' : '' }}">
                                <a class="nav-link pb-2" href="{{ route('connexion.logout') }}"><img src="{{ asset('images/logout.png') }}" class="btn-nav-img"></a>
                            </li>
                            @endauth
                        </ul>
                    </div>
                </nav>
            </div>
        </header>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li style="list-style: none"><i class="bi bi-exclamation-triangle"></i>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('status'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle"></i>{{ session('status') }}
        </div>
        @endif

        @yield('carousel')
    </div>

    @yield('content')

    <!-- info section -->
    <section class="info_section layout_padding">
        <div class="container">
            <div class="row">
                <div class="col-md-2 offset-md-3 mb-sm-4">
                    <div class="info_menu">
                        <h5>
                            LIENS RAPIDES
                        </h5>
                        <ul class="navbar-nav  ">
                            <li class="nav-item active">
                                <a class="nav-link" href="{{ $routeName === 'acceuil' ? '#top' : route('acceuil') }}"> Accueil <span class="sr-only">(actuel)</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/#a-propos"> À Propos </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/#evenements"> Évènements </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/#contact"> Contactez-Nous</a>
                            </li>
                            @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ $routeName === 'connexion' ? '#top' : route('connexion') }}"> Connexion </a>
                            </li>
                            @endguest
                            @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('utilisateurs.resetPasswd', session::get('utilisateur')) }}"> Modifier mon mot de passe</a>
                            </li>
                            @endauth
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 offset-md-1">
                    <div class="info_news">
                        <h5 class="mb-3">
                            POUR PLUS D'INFORMATIONS
                        </h5>
                        <div class="info_contact">
                            <span class="mb-1">
                                Pavillon des Sciences, 3500 Rue de Courval
                            </span>
                            <span class="mb-1">
                                Trois-Rivières, QC G9A 5E6
                            </span>
                            <span class="mb-1">
                                espaceitr@gmail.com
                            </span>
                            <span class="mb-1">
                                +1 (819) 456-7890
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @guest
    <script type="text/javascript">
        window.$crisp = [];
        window.CRISP_WEBSITE_ID = "7311cdc7-3125-4483-8421-82d77393fe66";
        (function() {
            d = document;
            s = d.createElement("script");
            s.src = "https://client.crisp.chat/l.js";
            s.async = 1;
            d.getElementsByTagName("head")[0].appendChild(s);
        })();
    </script>

    @endguest

    @yield('scriptBas')
</body>

</html>
