@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Pemesanan</h2>

        <form action="{{ route('user.booking.update', $booking->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="schedule_id" value="{{ $booking->schedule_id}}">

            <div class="seat-layout text-center">
                <div class="row justify-content-center">
                    @php
                        $groupedSeats = $availableSeats->groupBy(fn($seat) => substr($seat->seat_number, 0, 1));
                    @endphp

                    @foreach ($groupedSeats as $row => $seats)
                        <div class="d-flex align-items-center mb-2">
                            <span class="seat-row-label me-3"><strong>{{ $row }}</strong></span>
                            <!-- Label Baris -->
                            <div class="d-flex">
                                @foreach ($seats as $seat)
                                    <label class="seat me-2">
                                        <input type="checkbox" name="seat_id[]" value="{{ $seat->id }}"
                                            @if ($seat->status !== 'sedia') disabled @endif>
                                        <span
                                            class="seat-number btn btn-sm 
                                            @if ($seat->status === 'sedia') btn-outline-secondary 
                                            @else btn-danger disabled @endif">
                                            {{ substr($seat->seat_number, 1) }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="services mt-4">
                <h5>Layanan Tambahan</h5>
                <div class="row">
                    @foreach ($services as $item)
                        <div class="col-md-4">
                            <label class="form-check-label">
                                <input type="checkbox" name="services[]" value="{{ $item->id }}" class="form-check-input">
                                {{ $item->nama }} (Rp{{ number_format($item->price, 0, ',', '.') }})
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            @if (session('error'))
                <div class="alert alert-danger mt-3">
                    {{ session('error') }}
                </div>
            @endif
            <!-- Tombol Simpan -->
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            <a href="{{ route('user.booking.konfirmasi', ['scheduleId' => $booking->schedule_id]) }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
