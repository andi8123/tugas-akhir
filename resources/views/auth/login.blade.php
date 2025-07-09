@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <div class="container mt-4">
        <div class="mb-4 d-flex align-items-center justify-content-center flex-column">
            <img height="120" src="/images/logo.png" alt="Logo">
            <h1 class="h3 fw-bold">STMIK Admin - Login</h1>
        </div>
        <div class="card p-4 mx-auto" style="max-width: 600px">
            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input placeholder="Email" type="email" name="email" id="email" class="form-control" required>
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input placeholder="Password" type="password" name="password" id="password" class="form-control"
                        required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
@endsection
