@extends('layouts.app')

@section('script')
    <script src="{{ asset('js/popper.min.js') }}"></script>

    <script src="{{ asset('fullcalendar/packages/core/main.js') }}"></script>
    <script src="{{ asset('fullcalendar/packages/interaction/main.js') }}"></script>
    <script src="{{ asset('fullcalendar/packages/daygrid/main.js') }}"></script>
    <script src="{{ asset('fullcalendar/packages/timegrid/main.js') }}"></script>
    <script src="{{ asset('fullcalendar/packages/list/main.js') }}"></script>
@endsection

@section('style')
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('fonts/icomoon/style.css') }}">
    <link href="{{ asset('fullcalendar/packages/core/main.css') }}" rel='stylesheet' />
    <link href="{{ asset('fullcalendar/packages/daygrid/main.css') }}" rel='stylesheet' />
@endsection

@section('content')
    <section class="event_section user_section layout_padding">
        <div class="container">
            <div class="heading_container">
                <h3>
                    Gestion des horaires
                </h3>
                @auth
                <p>
                    <a href="{{route('disponibilites.create')}}"> Ajouter une disponibilité </a>
                </p>
                @endauth
            </div>
            <div class="event_container user_container">
                <div id='calendar-container'>
                    <div id='calendar'></div>
                </div>
                @php
                    echo "<script>
                            document.addEventListener('DOMContentLoaded', function()
                            {
                                var calendarEl = document.getElementById('calendar');
                                var calendar = new FullCalendar.Calendar(calendarEl,
                                {
                                    plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
                                    height: 'parent',
                                    header:
                                    {
                                        left: 'prev,next today',
                                        center: 'title',
                                        right: 'dayGridMonth,/*timeGridWeek,timeGridDay*/,listWeek'
                                    },
                                    defaultView: 'dayGridMonth',
                                    defaultDate: new Date(),
                                    navLinks: true,
                                    editable: true,
                                    eventLimit: true,
                                    events: [ ";
                @endphp
                @foreach($disponibilites as $disponibilite)
                    {
                        title: "{{$disponibilite->endroit}}",
                        start: "{{$disponibilite->debut}}",
                        end: "{{$disponibilite->fin}}",
                        url: "http://127.0.0.1:8000/disponibilites/{{$disponibilite->id}}"
                    },
                @endforeach
                @php
                    echo "]
                                });
                                calendar.render();
                            });
                        </script> ";
                @endphp
            </div>
        </div>
    </section>
@endsection
