@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <form action="/register" method="POST" style="width: 400px;">
            @csrf
            <h3 class="text-center mb-4">Register</h3>

            <div class="mb-3">
                <label for="fullName" class="form-label">Full Name</label>
                <input type="text" name="fullname" class="form-control" id="fullName" placeholder="Juan Dela Cruz">
            </div>

            <div class="mb-3">
                <label for="emailRegister" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="emailRegister" placeholder="Enter email">
            </div>

            <div class="mb-3">
                <label for="passwordRegister" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="passwordRegister" placeholder="Password">
            </div>

            <div class="mb-3">
                <label for="passwordConfirm" class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" id="passwordConfirm" placeholder="Confirm Password">
            </div>

            <button type="submit" class="btn btn-teal-600 w-100">Register</button>
        </form>
    </div>
@endsection
