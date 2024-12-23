@extends('layouts.admin.app');

@section('content')
    <form action="{{ url('admin/schedules/store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="film_id">Film</label>
            <select name="film_id" required>
                @foreach ($films as $film)
                    <option value="{{ $film->id }}">{{ $film->judul }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="studio">Studio:</label>
            <input type="text" inputmode="numeric" name="studio" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="price">Price:</label>
            <input type="text" inputmode="numeric" name="price" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="start_date">Tanggal Mulai:</label>
            <input type="date" name="start_date" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="end_date">Tanggal Selesai:</label>
            <input type="date" name="end_date" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="opening_time">Opening Time</label>
            <input type="time" name="start_time" required>
        </div>

        <div class="form-group">
            <label for="closing_time">Closing Time</label>
            <input type="time" name="end_time" required>
        </div>

        <button type="submit" class="btn btn-success">Buat Jadwal Otomatis</button>
    </form>

    <ul>
        @foreach ($schedules as $schedule)
            <li>
                <p>{{ $schedule->show_date }} - {{ $schedule->start_time }} s/d {{ $schedule->end_time }}</p>
                <a href="{{ route('schedule.seats', $schedule->id) }}">Pilih Kursi</a>
            </li>
        @endforeach
    </ul>
@endsection
