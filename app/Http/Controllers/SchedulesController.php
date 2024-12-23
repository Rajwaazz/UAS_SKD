<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Film;
use App\Models\Seats;
use Carbon\Carbon;

class SchedulesController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['film'])->latest()->paginate(10);
        return view('admin.dashboard.schedules', compact('schedules'));
    }

    public function create()
    {
        $data['films'] = Film::where('status', 'now playing')->get();
        $data['schedules'] = [];
        return view('admin.dashboard.createschedules', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'film_id' => 'required|exists:films,id',
            'studio' => 'required|integer|min:1|max:5',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'price' => 'required|numeric|min:0',
        ]);

        $film = Film::where('id',$request->film_id)->where('status', 'now playing')->first();
        if (!$film) {
            return back()->withErrors(['film' => 'Film not found or not currently playing.']);
        }
        $filmDuration = $film->duration;

        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        $startTime = Carbon::parse($request->input('start_time'));
        $endTime = Carbon::parse($request->input('end_time'));

        $currentDate = $startDate->copy();

        // Loop untuk membuat jadwal per hari dalam rentang waktu yang diberikan
        while ($currentDate->lte($endDate)) {
            $currentStartTime = $startTime->copy();

            // Loop untuk membuat waktu tayang film berdasarkan durasi film
            while ($currentStartTime->copy()->addMinutes($filmDuration)->lte($endTime)) {
                $schedule = Schedule::create([
                    'films_id' => $film->id,
                    'studio' => $request->studio,
                    'show_date' => $currentDate->format('Y-m-d'),
                    'start_time' => $currentStartTime->format('H:i'),
                    'end_time' => $currentStartTime->copy()->addMinutes($filmDuration)->format('H:i'),
                    'total_seats' => 150,
                    'price' => $request->price,
                ]);

                $this->createSeats($schedule);  // Membuat kursi untuk jadwal tersebut
                $currentStartTime->addMinutes($filmDuration); // Pindah ke waktu tayang berikutnya
            }

            $currentDate = $currentDate->addDay(); // Pindah ke hari berikutnya
        }

        return redirect()->route('admin.dashboard.schedules')->with('success', 'Schedules berhasil dibuat.');
    }


    private function createSeats($schedule)
    {
        $rows = range('A', 'I'); // Baris A sampai I
        $seatsPerRow = 15; // 15 kursi per baris

        foreach ($rows as $row) {
            for ($i = 1; $i <= $seatsPerRow; $i++) {
                Seats::create([
                    'schedule_id' => $schedule->id,
                    'seat_number' => $row . $i, // Format kursi, misal A1, A2, ..., I15
                    'status' => 'sedia', // Status default
                ]);
            }
        }
    }

    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        return redirect()->route('admin.dashboard.schedules')->with('success', 'Schedule has been deleted');
    }
}