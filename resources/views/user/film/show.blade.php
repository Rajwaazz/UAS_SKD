@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="card shadow mx-auto" style="max-width: 800px;">
        <!-- Poster di tengah -->
        <div class="text-center p-4">
            <img class="img-fluid rounded" 
                 src="{{ asset('storage/' . $film->poster) }}" 
                 alt="{{ $film->judul }}"
                 style="max-height: 400px; object-fit: cover;">
        </div>

        <!-- Informasi film -->
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h1 class="card-title h2 mb-0">{{ $film->judul }}</h1>
                <span class="badge bg-success">{{ $film->duration }} Minutes</span>
            </div>

            <div class="mb-4">
                <div class="row mb-2">
                    <div class="col-md-3 text-muted">Genre</div>
                    <div class="col-md-9">: {{ $film->genre }}</div>
                </div>
                
                <div class="row mb-2">
                    <div class="col-md-3 text-muted">Status</div>
                    <div class="col-md-9">: {{ $film->status }}</div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-3 text-muted">Tanggal Rilis</div>
                    <div class="col-md-9">: {{ date('d/m/Y', strtotime($film->tanggalRilis)) }}</div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-3 text-muted">Durasi</div>
                    <div class="col-md-9">: {{ $film->duration }} menit</div>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="mt-4">
                <h5 class="mb-3">Deskripsi</h5>
                <p class="text-muted">{!! $film->deskripsi !!}</p>
            </div>

            <!-- Jadwal untuk Film -->
            <div class="mt-4">
                <h5 class="mb-3">Jadwal untuk Film {{ $film->judul }}</h5>
                @if($schedules->isEmpty())
                    <div class="alert alert-danger">
                        Jadwal tidak tersedia
                    </div>
                @else
                    <div class="row g-2">
                        @foreach ($schedules as $schedule)
                            <div class="col-12 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="mb-1"><strong>Tanggal:</strong> {{ $schedule->show_date }}</div>
                                                <div><strong>Jam:</strong> {{ $schedule->start_time }}</div>
                                            </div>
                                            <a href="{{ route('user.booking.index', $schedule->id) }}" 
                                               class="btn btn-primary">
                                                Pesan Tiket
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection