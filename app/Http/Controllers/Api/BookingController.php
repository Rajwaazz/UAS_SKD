<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Booking;
use App\Models\Seats;
use App\Models\service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function list()
    {
        try {
            $bookings = Booking::with(['schedule.film', 'bookingseat', 'bookingservice'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'List Booking',
                'data' => $bookings
            ]);
        } catch (\Exception $err) {
            return response()->json(['status' => false, 'message' => $err->getMessage()]);
        }
    }
    public function index($scheduleId)
    {
        try {
            $schedule = Schedule::findorfail($scheduleId);
            $film = $schedule->film;
            $availableSchedules = Schedule::where('films_id', $film->id)->get();
            $availableSeats = Seats::where('schedule_id', $scheduleId)
                ->select([
                    'id',
                    'seat_number',
                    'status'
                ])
                ->get();
            $service = service::all();

            return response()->json([
                'status' => true,
                'message' => 'Booking List',
                'data' => [
                    'schedule' => $schedule,
                    'film' => $film,
                    'availableSchedules' => $availableSchedules,
                    'availableSeats' => $availableSeats,
                    'service' => $service,
                ]
            ], 200);
        } catch (\Exception $err) {
            return response()->json(['status' => false, 'message' => $err->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $userId = $request->user_id ?? Auth::id();

        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|exists:schedules,id',
            'seat_id' => 'required|array',
            'seat_id.*' => 'exists:seats,id',
            'services' => 'nullable|array',
            'services.*' => 'nullable|exists:services,id',
            'user_id' => 'nullable|exists:users,id',
        ], [
            'schedule_id.required' => 'Schedule ID is required',
            'seat_id.required' => 'At least one seat must be selected',
            'seat_id.*.exists' => 'One or more selected seats are invalid'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }

        try {
            $schedule = Schedule::findOrFail($request->schedule_id);

            $seats = Seats::whereIn('id', $request->seat_id)->get();
            foreach ($seats as $seat) {
                if ($seat->status != 'sedia') {
                    return response()->json([
                        'status' => false,
                        'message' => 'One or more selected seats are already taken or unavailable.'
                    ], 400);
                }
            }

            foreach ($seats as $seat) {
                if ($seat->status != 'sedia') {
                    return $this->errorResponse('Chair Has Been Chosen!', 400);
                }
            }

            $totalPrice = $schedule->price * count($seats);

            $booking = Booking::create([
                'user_id' => $userId,
                'schedule_id' => $schedule->id,
                'total_price' => $totalPrice,
                'status' => 'pending'
            ]);

            $booking->bookingseat()->attach($seats);

            $seats->each(function ($seat) {
                $seat->update(['status' => 'tidak tersedia']);
            });

            if ($request->has('services')) {
                $services = Service::findMany($request->services);
                foreach ($services as $service) {
                    $booking->bookingservice()->attach($service->id, ['jumlah' => 1]);
                    $totalPrice += $service->price;
                }
            }

            $booking->update(['total_price' => $totalPrice]);
            return response()->json([
                'status' => true,
                'message' => 'Booking created successfully'
            ], 201);
        } catch (\Exception $err) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }
    }

    public function konfirmasi($scheduleId)
    {
        try {
            $booking = Booking::where('user_id', Auth::id())->where('schedule_id', $scheduleId)->latest()->first();

            if (!$booking) {
                return response()->json(['status' => false, 'message' => 'Booking Not Found'], 404);
            }

            $schedule = Schedule::with('film')->findOrFail($booking->schedule_id);
            $seat = $booking->bookingseat;
            $totalPrice = $booking->total_price;

            return response()->json([
                'status' => true,
                'data' => [
                    'booking' => $booking,
                    'schedule' => $schedule,
                    'seat' => $seat,
                    'totalPrice' => $totalPrice
                ]
            ], 200);
        } catch (\Exception $err) {
            return response()->json(['status' => false, 'message' => $err->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            // Temukan pemesanan berdasarkan ID
            $booking = Booking::findOrFail($id);

            // Kembalikan respons dengan data pemesanan
            return response()->json([
                'status' => true,
                'data' => $booking,
            ], 200);
        } catch (\Exception $err) {
            return response()->json([
                'status' => false,
                'message' => 'Pemesanan tidak ditemukan',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $scheduleDate = $booking->schedule->date;

        if ($scheduleDate > now()->toDateString()) {
            return redirect()->back()->withErrors('Pemesanan Sudah Tidak Dapat Diedit');
        }

        $request->validate([
            'seat_id' => 'required|array',
            'seat_id.*' => 'exists:seats,id',
            'services' => 'array',
            'services.*' => 'exists:services,id',
        ]);

        $booking->bookingseat->each(function ($seat) {
            $seat->update(['status' => 'sedia']);
        });

        $booking->bookingseat()->detach();

        $seats = Seats::whereIn('id', $request->seat_id)->get();
        foreach ($seats as $item) {
            $item->update(['status' => 'tidak tersedia']);
        }
        $booking->bookingseat()->attach($seats);

        $totalPrice = $booking->schedule->price * count($seats);

        if ($request->has('services')) {
            $booking->bookingservice()->detach();
            $services = Service::findMany($request->services);
            foreach ($services as $service) {
                $booking->bookingservice()->attach($service->id, ['jumlah' => 1]);
                $totalPrice += $service->price; // Tambahkan harga layanan ke total
            }
        }

        $booking->update(['total_price' => $totalPrice]);

        return redirect()->route('user.booking.konfirmasi', ['scheduleId' => $booking->schedule_id])
            ->with('success', 'Pemesanan berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            $scheduleDate = $booking->schedule->date;

            if ($scheduleDate > now()->toDateString()) {
                return response()->json(['status' => false, 'message' => 'Booking Cannot Be Deleted'], 400);
            }

            $booking->bookingseat->each(function ($seat) {
                $seat->update(['status' => 'sedia']);
            });

            $booking->bookingseat()->detach();
            $booking->bookingservice()->detach();
            $booking->delete();
            return response()->json(['status' => true, 'message' => 'Delete Booking'], 204);
        } catch (\Exception $err) {
            return response()->json(['status' => false, 'message' => $err->getMessage()]);
        }
    }
}
