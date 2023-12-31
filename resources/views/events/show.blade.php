@extends('layouts.main')
@section('title', '{{ $event->Title }}')

@section('content')

  <div class="col-md-10 offset-md-1">
    <div class="row">
      <div id="image-container" class="col-md-6">
        <img src="/img/events/{{ $event->Image }}" class="img-fluid" alt="{{ $event->Title }}">
      </div>
      <div id="info-container" class="col-md-6">
        <h1>{{ $event->title }}</h1>
        <p class="event-city"><ion-icon name="location-outline"></ion-icon> {{ $event->City }}</p>
        <p class="events-participants"><ion-icon name="people-outline"></ion-icon> {{ count($event->users) }} Participantes</p>
        <p class="event-owner"><ion-icon name="star-outline"></ion-icon> {{ $eventOwner['name'] }}</p>
        @if(!$hasUserJoined)
            <form action="/events/join/{{ $event->id }}" method="POST">
                @csrf
                <a href="/events/join/{{ $event->id }}" class="btn btn-primary" id="event-submit" onclick="event.preventDefault(); this.closest('form').submit();">CONFIRMAR PRESENÇA</a>
            </form>
        @else
            <p class="already-joined-msg"> Você já é participante deste evento. </p>
        @endif
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
