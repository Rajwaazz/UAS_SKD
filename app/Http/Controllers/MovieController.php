<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
// use Illuminate\Container\Attributes\Storage;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function index()
    {
        $data['films'] = \App\Models\Film::latest()->paginate(10);
        return view('admin.dashboard.film', $data);
    }

    public function create()
    {
        return view('admin.dashboard.createfilm');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'poster' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'genre' => 'required|array',
            'tanggalRilis' => 'required|date',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:now playing,upcoming,expired',
        ]);

        if ($request->has('genre')) {
            $validatedData['genre'] = implode(', ', $request->input('genre'));
        }

        if ($request->hasFile('poster')) {
            $validatedData['poster'] = $request->file('poster')->store('posters', 'public');
        }

        Film::create($validatedData);

        return redirect()->route('admin.dashboard.film')->with('success', 'Film Berhasil Ditambahkan');
    }

    public function edit(Film $film)
    {
        return view('admin.dashboard.editfilm', compact('film'));
    }

    public function update(Request $request, Film $film)
    {
        $validatedData = $request->validate([
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'genre' => 'required|array',
            'tanggalRilis' => 'required|date',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:now playing,upcoming,expired',
        ]);

        if ($request->has('genre')) {
            $validatedData['genre'] = implode(', ', $request->input('genre'));
        }

        // Jika ada file poster yang diunggah
        if ($request->hasFile('poster')) {
            // Hapus poster lama jika ada
            if ($film->poster) {
                Storage::delete('public/' . $film->poster);
            }
            // Simpan poster baru
            $validatedData['poster'] = $request->file('poster')->store('posters', 'public');
        }

        // Update film dengan data yang sudah divalidasi
        $film->update($validatedData);

        return redirect()->route('admin.dashboard.film')->with('success', 'Film Telah Terupdate');
    }


    public function destroy(Film $film)
    {
        // Hapus poster dari storage jika ada
        if ($film->poster) {
            Storage::delete('public/' . $film->poster);
        }

        // Hapus film dari database
        $film->delete();

        return redirect()->route('admin.dashboard.film')->with('success', 'Film Telah Dihapus');
    }
}
