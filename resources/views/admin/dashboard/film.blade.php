@extends('layouts.admin.app')

@section('content')
    <h1>
        Film List</h1>
    <a href="{{ url('admin/movies/create') }}" class="btn btn-primary">Add New Film</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Poster</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Genre</th>
                <th>Tanggal Rilis</th>
                <th>Durasi</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($films as $item)
                <tr>
                    <td>
                        @if ($item->poster)
                            <img src="{{ asset('storage/' . $item->poster) }}" alt="{{ $item->title }}" width="100">
                        @endif
                    </td>
                    <td>{{ $item->judul }}</td>
                    <td>{!! $item->deskripsi !!}</td>
                    <td>{{ $item->genre }}</td>
                    <td>{{ $item->tanggalRilis }}</td>
                    <td>{{ $item->duration }}</td>
                    <td>{{ $item->status }}</td>
                    <td>
                        <a href="{{ route('admin.dashboard.editfilm', $item) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('admin.dashboard.film.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus film ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
