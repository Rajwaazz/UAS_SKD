@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Verifikasi OTP</div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('showotp') }}">
                        @csrf
                        <div class="form-group">
                            <label>Masukkan Kode OTP</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="otp" 
                                   required 
                                   maxlength="6" 
                                   placeholder="Masukkan 6 digit kode OTP">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Verifikasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection