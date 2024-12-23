@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Konfirmasi Pemesanan</h2>

        <div class="card">
            <div class="card-header">
                Detail Pemesanan
            </div>
            <div class="card-body">
                <h5>Film: {{ $schedule->film->judul }}</h5>
                <p>Tanggal dan Waktu: {{ $schedule->show_date }} - {{ $schedule->start_time }}</p>
                <p>Kursi yang Dipesan: @foreach ($seat as $item)
                        {{ $item->seat_number }}@if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                </p>
                <h5>Layanan Tambahan:</h5>
                @foreach ($booking->bookingservice as $service)
                    <p>{{ $service->nama }}
                        (Rp{{ number_format($service->pivot->jumlah * $service->price, 0, ',', '.') }})
                    </p>
                @endforeach
                <p><strong>Total Harga: Rp{{ number_format($totalPrice, 0, ',', '.') }}</strong></p>
                <p>Status: {{ $booking->status }}</p>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('user.booking.edit', $booking->id) }}" class="btn btn-warning">Edit Pemesanan</a>

            <form action="{{ route('user.booking.destroy', $booking->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" 
                        onclick="return confirm('Apakah Anda yakin ingin menghapus pemesanan ini?')">Hapus Pemesanan</button>
            </form>
        </div>

        <a href="{{ route('user.booking.index', ['scheduleId' => $schedule->id]) }}" class="btn btn-primary mt-3">Kembali ke
            Halaman Pemesanan</a>
    </div>
@endsection
