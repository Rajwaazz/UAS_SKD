@extends('layouts.admin.app')

@section('content')
    <h1>Film Add</h1>
    <form action="{{ route('admin.movies.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="poster">Poster</label>
            <input type="file" class="form-control" id="poster" name="poster">
        </div>

        <div class="form-group">
            <label for="judul">Judul</label>
            <input type="text" class="form-control" id="judul" name="judul"
                value="{{ old('judul', $film->judul ?? '') }}" required>
        </div>

        <div class="form-group">
            <label class="font-weight-bold">Deskripsi</label>
            <textarea class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" id="deskripsi" rows="5" placeholder="Masukkan Deskripsi Film">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="form-group">
            <label for="genre">Genre</label><br>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="genre[]" value="Action" id="action"
                    {{ in_array('Action', old('genre', [])) ? 'checked' : '' }}>
                <label class="form-check-label" for="action">Action</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="genre[]" value="Comedy" id="comedy"
                    {{ in_array('Comedy', old('genre', [])) ? 'checked' : '' }}>
                <label class="form-check-label" for="comedy">Comedy</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="genre[]" value="Drama" id="drama"
                    {{ in_array('Drama', old('genre', [])) ? 'checked' : '' }}>
                <label class="form-check-label" for="drama">Drama</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="genre[]" value="Horror" id="horror"
                    {{ in_array('Horror', old('genre', [])) ? 'checked' : '' }}>
                <label class="form-check-label" for="horror">Horror</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="genre[]" value="Romance" id="romance"
                    {{ in_array('Romance', old('genre', [])) ? 'checked' : '' }}>
                <label class="form-check-label" for="romance">Romance</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="genre[]" value="Animation" id="animation"
                    {{ in_array('Animation', old('genre', [])) ? 'checked' : '' }}>
                <label class="form-check-label" for="animation">Animation</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="genre[]" value="Thriller" id="thriller"
                    {{ in_array('Thriller', old('genre', [])) ? 'checked' : '' }}>
                <label class="form-check-label" for="thriller">Thriller</label>
            </div>
        </div>

        <div class="form-group">
            <label for="tanggalRilis">Tanggal Rilis</label>
            <input type="date" class="form-control" id="tanggalRilis" name="tanggalRilis"
                value="{{ old('tanggalRilis', $film->tanggalRilis ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="duration">Durasi</label>
            <input type="number" class="form-control" id="duration" name="duration"
                value="{{ old('duration', $film->duration ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" required>
                <option value="now playing">Now Playing</option>
                <option value="upcoming">UpComing</option>
                <option value="expired">Expired</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Submit</button>
    </form>

    <script src="https://cdn.ckeditor.com/4.18.0/standard/ckeditor.js"></script>
    <script>
        // Inisialisasi CKEditor
        CKEDITOR.replace('deskripsi');
    </script>
@endsection
