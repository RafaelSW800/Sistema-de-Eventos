<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;

class EventController extends Controller
{
    public function index() {
        $search = request('search');

        if($search) {
            $events = Event::where([['title', 'like', '%'.$search.'%']])->get();
        } else {
            $events = Event::all();
        }
        return view('welcome',['events' => $events, 'search' => $search]);

    }

    public function create() {
        return view('events.create');
    }

    public function store(Request $request) {

        $event = new Event;

        $event->Title = $request->title;
        $event->Date = $request->date;
        $event->City = $request->city;
        $event->Private = $request->private;
        $event->Description = $request->description;
        $event->Items = $request->items;

        // Image Upload
        if($request->hasFile('image') && $request->file('image')->isValid()) {

            $requestImage = $request->image;
            $extension = $requestImage->extension();
            //Essa linha vai permitir que os arquivos de imagens adicionados ao documento pelo usuário recebem nomes únicos.
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $requestImage->move(public_path('img/events'), $imageName);
            $event->Image = $imageName;
        }
        $user = auth()->user();
        $event->user_id = $user->id;
        $event->save();

        return redirect('/')->with('msg', 'Evento criado com sucesso!');
    }

    public function show($id) {

        $event = Event::findOrFail($id);

        $user = auth()->user();
        $hasUserJoined = false;

        if($user){

            $userEvents = $user->eventsAsParticipant->toArray();

            foreach($userEvents as $userEvent){
                if($userEvent['id'] == $id){
                    $hasUserJoined = true;
                }
            }
        }
        $eventOwner = User::where('id', $event->user_id)->first()->toArray(); //Identifica a identidade do usuário

        return view('events.show', ['event' => $event, 'eventOwner' => $eventOwner, 'hasUserJoined' => $hasUserJoined]);
    }

    public function dashboard(){
        $user = auth()->user();
        $events = $user->events;

        $eventsAsParticipant = $user->eventsAsParticipant;

        return view('events.dashboard', ['events' => $events, 'eventsAsParticipant' => $eventsAsParticipant]);
    }

    public function destroy($id){
        Event::findOrFail($id)->delete(); //vai encontrar esse evento no banco de dados pela ID passado da view

        return redirect('/dashboard')->with('msg', 'Evento excluído com sucesso!');
    }

    public function edit($id){

        $user = auth()->user();
        $event = Event::findOrFail($id);

        if($user->id != $event->user_id){
            return redirect('/dashboard');
        }

        return view('events.edit', ['event' => $event]);
    }

    public function update(Request $request){

        $data = $request->all();

        // Image Upload
        if($request->hasFile('image') && $request->file('image')->isValid()) {

            $requestImage = $request->image;
            $extension = $requestImage->extension();
            //Essa linha vai permitir que os arquivos de imagens adicionados ao documento pelo usuário recebem nomes únicos.
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $requestImage->move(public_path('img/events'), $imageName);
            $data['image'] = $imageName;
        }
        Event::findOrFail($request->id)->update($data);

        return redirect('/dashboard')->with('msg', 'Evento editado com sucesso!');
    }
        public function joinEvent($id){
            $user = auth()->user();
            $user->eventsAsParticipant()->attach($id);
            $event = Event::findOrFail($id);

            return redirect('/dashboard')->with('msg', 'Sua presença está confirmada no evento ' . $event->Title);
        }

        public function leaveEvent($id){

            $user = auth()->user();
            $user->eventsAsParticipant()->detach($id);
            $event = Event::findOrFail($id);

            return redirect('/dashboard')->with('msg', 'Você saiu do evento: ' . $event->Title);
        }
}

