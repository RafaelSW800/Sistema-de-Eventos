@extends('layouts.main')
@section('title', '{{$event->Title}}')

@section('content')

  <div class="col-md-10 offset-md-1">
    <div class="row">
      <div id="image-container" class="col-md-6">
        <img src="/img/events/{{ $event->Image }}" class="img-fluid" alt="{{ $event->Title }}">
      </div>
      <div id="info-container" class="col-md-6">
        <h1>{{ $event->title }}</h1>
        <p class="event-city"><ion-icon name="location-outline"></ion-icon> {{ $event->City }}</p>
        <p class="events-participants"><ion-icon name="people-outline"></ion-icon> X Participantes</p>
        <p class="event-owner"><ion-icon name="star-outline"></ion-icon> Dono do Evento</p>
        <a href="#" class="btn btn-primary" id="event-submit">Confirmar Presença</a>
        <h3>O evento conta com:</h3>
        <ul id="items-list">
        @foreach($event->Items as $Item)
          <li><ion-icon name="play-outline"></ion-icon> <span>{{ $Item }}</span></li>
        @endforeach
        </ul>
      </div>
      <div class="col-md-12" id="description-container">
        <h3>Sobre o evento:</h3>
        <p class="event-description">{{ $event->Description }}</p>
      </div>
    </div>
  </div>

@endsection
