@extends('layouts.app')

@section('style')
    <link href="{{ asset('css/cedric.css') }}" rel="stylesheet">
@endsection

@section('script')
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/jquery-input-mask-phone-number.min.js') }}"></script>
@endsection

@section('content')

    <section class="course_section layout_padding">
        <div class="side_img">
            <img src="images/side-img.png" alt="" />
        </div>
        <div class="container">
            <div class="heading_container">
                <h3>
                    Tutoriels
                </h3>
                <p>
                    Toutes les ressources pour les outils les plus utilisés!
                </p>
            </div>
            <div class="container">
                <div class="container-fluid mt-8">
                    <div class="row justify-content-center">
                        @foreach($tutos as $item)
                            @foreach($item["items"] as $tuto)
                                <div class="col-auto mb-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ $tuto["snippet"]["title"] }}</h5>
                                        </div>
                                        <img src="{{ $tuto["snippet"]["thumbnails"]["maxres"]["url"] ?? $tuto["snippet"]["thumbnails"]["high"]["url"] }}" class="img" data-titre="{{ $tuto["snippet"]["title"] }}" data-id="{{ $tuto["id"] }}" alt="{{ $tuto["snippet"]["title"] }}">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2 text-muted"></h6>
                                            <p id="description" class="card-text limit">{{ $tuto["snippet"]["description"] }}</p>
                                        </div>

                                        <div class="card-footer">
                                            <a href="{{ "https://www.youtube.com/watch?v=" . $tuto["id"] }}" target="_blank" class="btn btn-participation btn-sm" data-titre="{{ $tuto["snippet"]["title"] }}" data-id="">Visionner</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <a href="https://www.youtube.com/c/MicrosoftMechanicsSeries" target="_blank" class="btn btn-form btn-primary mr-0">Tous les tutoriels</a>
        </div>
    </section>

@endsection
