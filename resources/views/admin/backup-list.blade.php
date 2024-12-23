@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <h1>Daftar Backup Database</h1>

        @php
            \Log::info('Backups in view:', $backups);
        @endphp

        @if (count($backups) == 0)
            <div class="alert alert-warning">
                Tidak ada file backup yang ditemukan.
                Path: {{ storage_path('app/backups') }}
            </div>
        @endif

        @if (session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif
        @if (session('error'))
            <p style="color: red;">{{ session('error') }}</p>
        @endif

        <form action="{{ route('backup') }}" method="get">
            <button type="submit" class="btn btn-primary">Backup Database</button>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th>Nama File</th>
                    <th>Ukuran</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($backups as $backup)
                    <tr>
                        <td>{{ $backup['filename'] }}</td>
                        <td>{{ $backup['size'] }} MB</td>
                        <td>{{ $backup['created_at'] }}</td>
                        <td>
                            <a href="{{ route('backup.download', ['filename' => $backup['filename']]) }}"
                                class="btn btn-primary btn-sm">Unduh</a>
                            <form action="{{ route('backup.delete', ['filename' => $backup['filename']]) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus backup ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada file backup</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <form action="{{ route('restore') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="backup_file">Upload Backup File:</label>
            <input type="file" name="backup_file" required>
            <button type="submit">Restore</button>
        </form>
    </div>
@endsection
