@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="mb-4">My Profile</h3>

        <div class="row">
            <!-- Left: Avatar -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img
                            src="{{ asset('storage/avatar/' . Auth::user()->avatar) }}"
                            class="rounded-circle mb-3"
                            width="150"
                            height="150"
                            alt="Profile Picture"
                        >
                        <form action="{{ route('profile.update_avatar') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <input type="file" name="avatar" class="form-control-file">
                            </div>
                            <button type="submit" class="btn btn-teal-600 btn-block mt-2">Change Avatar</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right: Profile Info and Password -->
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">Update Information</div>
                    <div class="card-body">
                        <form action="{{ route('profile.update_info') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" name="name" value="{{ Auth::user()->name }}" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" value="{{ Auth::user()->email }}" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Change Password</div>
                    <div class="card-body">
                        <form action="{{ route('profile.update_password') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="new_password_confirmation">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-warning">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
