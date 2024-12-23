@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar item Saya</h1>
    @if($bookings->isEmpty())
        <p>Tidak ada item yang ditemukan.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Film</th>
                        <th>Jadwal</th>
                        <th>Jumlah Kursi</th>
                        <th>Layanan</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->schedule->film->judul }}</td>
                            <td>{{ $item->schedule->show_date }} {{ $item->schedule->start_time }}</td>
                            <td>{{ $item->bookingseat->count() }}</td>
                            <td>
                                @if($item->bookingservice->isEmpty())
                                    Tidak ada
                                @else
                                    <ul>
                                        @foreach($item->bookingservice as $service)
                                            <li>(x{{ $service->pivot->jumlah }})</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td>Rp{{ number_format($item->total_price, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($item->status) }}</td>
                            <td>
                                <a href="{{ route('user.booking.konfirmasi', ['scheduleId' => $item->schedule_id]) }}" class="btn btn-info btn-sm">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
