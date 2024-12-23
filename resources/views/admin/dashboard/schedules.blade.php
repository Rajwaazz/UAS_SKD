@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Jadwal Film</h1>
        <a href="{{ route('admin.dashboard.createschedules') }}" class="btn btn-primary mb-3">Tambah Jadwal Baru</a>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Film</th>
                    <th>Studio</th>
                    <th>Tanggal Tayang</th>
                    <th>Jam Tayang</th>
                    <th>Total Kursi</th>
                    <th>Price</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $index => $schedule)
                    <tr>
                        <td>{{ $index + $schedules->firstItem() }}</td>
                        <td>{{ $schedule->film->judul }}</td>
                        <td>{{ $schedule->studio }}</td>
                        <td>{{ $schedule->show_date }}</td>
                        <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                        <td>{{ $schedule->total_seats }}</td>
                        <td>{{ $schedule->price }}</td>
                        <td>
                            <form action="{{ url('admin/schedules/' . $schedule->id) }}" method="POST"
                                style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada jadwal tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $schedules->links() }}
    </div>
@endsection
