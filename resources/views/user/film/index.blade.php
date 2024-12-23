@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <form action="{{ route('user.film.index') }}" method="GET" class="mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" 
                        placeholder="Cari judul atau deskripsi" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="genre" class="form-select">
                        <option value="">Pilih Genre</option>
                        <option value="Action" {{ request('genre') == 'Action' ? 'selected' : '' }}>Action</option>
                        <option value="Drama" {{ request('genre') == 'Drama' ? 'selected' : '' }}>Drama</option>
                        <option value="Comedy" {{ request('genre') == 'Comedy' ? 'selected' : '' }}>Comedy</option>
                        <option value="Horror" {{ request('genre') == 'Horror' ? 'selected' : '' }}>Horror</option>
                        <option value="Romance" {{ request('genre') == 'Romance' ? 'selected' : '' }}>Romance</option>
                        <option value="Animation" {{ request('genre') == 'Animation' ? 'selected' : '' }}>Animation</option>
                        <option value="Thriller" {{ request('genre') == 'Thriller' ? 'selected' : '' }}>Thriller</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="release_date" class="form-control" 
                        value="{{ request('release_date') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Cari</button>
                </div>
            </div>
        </form>

        <div class="row g-4">
            @foreach($film as $item)
            <div class="col-md-3">
                <div class="card h-100">
                    <img src="{{ asset('storage/' . $item->poster) }}" class="card-img-top" alt="{{ $item->judul }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->judul }}</h5>
                        <div class="movie-info">
                            <span class="badge bg-primary me-2">{{ $item->genre }}</span>
                            <span class="badge {{ $item->status === 'Upcoming' ? 'bg-warning' : 'bg-success' }}">
                                {{ $item->status }}
                            </span>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('user.film.show', $item->id) }}" 
                                class="btn btn-primary w-100">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="mt-8">
            {{ $film->links() }}
        </div>
    </div>
@endsection
