<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">

        <!-- Bootstrap 4 CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- jQuery (required for $.ajax) -->
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

        <!-- Bootstrap 4 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

        <!-- Sweet Alert 2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Custom JS -->
        <script src="{{ asset('js/script.js') }}"></script>
        <title>BlogSpace - Website</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark custom-navbar">
            <x-title />

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarScroll">
                <ul class="navbar-nav mr-auto my-2 my-lg-0 navbar-nav-scroll" style="max-height: 100px;">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('home') }}">Home <span class="sr-only">(current)</span></a>
                    </li>

                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="/my-posts">My Posts</a>
                        </li>
                    @endauth

                </ul>
                @auth
                    <div class="d-flex align-items-center text-white">

                        <span class="mr-2">{{ Auth::user()->name }}</span>

                        <div class="dropdown">

                            <!-- Clickable Image -->
                            <div role="button" class="avatar" data-toggle="dropdown">
                                <x-avatar avatar="{{ Auth::user()->avatar }}" name="{{ Auth::user()->name }}" />

                            </div>

                            <!-- Dropdown Menu -->
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ route('profile') }}">Profile</a>
                                <a  class="dropdown-item" href="/my-posts">My Posts</a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{ route('logout') }}">
                                    Log out
                                </a>
                            </div>

                        </div>
                    </div>
                @else
                    <a href="/login" class="btn btn-outline-light mr-2">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i> Log in
                    </a>
                    <a href="/register" class="btn btn-outline-light">
                        <i class="fa-solid fa-user-plus"></i> Register
                    </a>
                @endauth
            </div>
        </nav>

        <main>
            @yield('content')
        </main>

        @if (session('success'))
            <script>
                Swal.fire({
                    title: "Success!",
                    text: "{{ session('success') }}",
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2500
                });
            </script>
        @elseif (session('error'))
            <script>
                Swal.fire({
                    title: "Error!",
                    text: "{{ session('error') }}",
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 2500
                });
            </script>
        @elseif ($errors->any())
            <script>
                Swal.fire({
                    title: "Error!",
                    text: "{{ $errors->first() }}",
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 2500
                });
            </script>
        @elseif (session('info'))
            <script>
                Swal.fire({
                    title: "Info!",
                    text: "{{ session('info') }}",
                    icon: 'info',
                    showConfirmButton: false,
                    timer: 2500
                });
            </script>
        @endif
    </body>
</html>
