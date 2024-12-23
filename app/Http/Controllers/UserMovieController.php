<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Models\Schedule;

class UserMovieController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $genre = $request->input('genre');
        $tanggalrilis = $request->input('tanggalrilis');

        $query = Film::query();

        if ($search) {
            $query->where('judul', 'like',  '%' . $search . '%')
                ->orWhere('deskripsi', 'like',  '%' . $search . '%');
        }
        if ($genre) {
            $query->where('genre', 'like',  '%' . $genre . '%');
        }
        if ($tanggalrilis) {
            $query->where('tanggalRilis', '=', $tanggalrilis);
        }

        $film = $query->latest()->paginate(10);
        return view('user.film.index', compact('film'));
    }

    public function show($id)
    {
        $film = Film::findorfail($id);
        $schedules = Schedule::where('films_id', $film->id)->get();
        return view('user.film.show', compact('film', 'schedules'));
    }
}
