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
        $eventOwner = User::where('id', $event->user_id)->first()->toArray(); //Identifica a identidade do usuário

        return view('events.show', ['event' => $event, 'eventOwner' => $eventOwner]);
    }
    public function dashboard(){
        $user = auth()->user();
        $events = $user->events;

        return view('events.dashboard', ['events' => $events]);
    }
}

