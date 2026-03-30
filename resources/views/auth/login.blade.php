@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <form action="/login" method="POST" style="width: 400px;">
            @csrf
            <h3 class="text-center mb-4">Login</h3>
            <div class="mb-3">
                <label for="emailLogin" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="emailLogin" placeholder="Enter email">
            </div>
            <div class="mb-3">
                <label for="passwordLogin" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="passwordLogin" placeholder="Password">
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Remember Me</label>
            </div>

            <button type="submit" class="btn btn-teal-600 w-100">Login</button>
        </form>
    </div>
@endsection
