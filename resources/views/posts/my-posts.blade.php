@extends('layouts.app')

@section('content')
    @include('posts.partials.create_post_modal')
    @include('posts.partials.update_post_modal')

    <div class="container">
        <h3 class="mb-4">My Posts</h3>

        @foreach($posts as $post)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <!-- Post Header -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <!-- Left: Avatar + User Info -->
                        <div class="d-flex align-items-center">
                            <!-- User avatar -->
                            <x-avatar avatar="{{ Auth::user()->avatar }}" name="{{ Auth::user()->name }}" />

                            <!-- Name & timestamp -->
                            <div class="ml-2">
                                <h6 class="mb-0">{{ ucwords(Auth::user()->name) }}</h6>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}</small>
                            </div>
                        </div>

                        <!-- Right: Actions (optional) -->
                        <div class="dropdown">
                            <a href="#" class="text-dark" data-toggle="dropdown" aria-expanded="false" style="text-decoration: none;">
                                <i class="fa-solid fa-ellipsis fa-xl"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <div class="dropdown-item" data-toggle="modal" data-target="#updatePostModal" onclick="update_post('{{ $post->id }}', '{{ $post->title }}', '{{ $post->content }}', '{{ $post->image }}')">Edit Post</div>
                                <div class="dropdown-item" onclick="delete_post('{{ $post->id }}')">Delete Post</div>
                            </div>
                        </div>
                    </div>

                    <!-- Post Content -->
                    <h5 class="card-title">{{ $post->title }}</h5>

                    @if ($post->image)
                        <div style="height: 300px; overflow: hidden;">
                            <img src="{{ $post->image ? asset('storage/post/' . $post->image) : asset('images/default-cover.jpg') }}"
                                alt="{{ $post->title }}"
                                class="img-fluid w-100"
                                style="object-fit: fit; height: 100%;">
                        </div>
                    @endif

                    <p class="card-text mt-2">{{ $post->content }}</p>

                    <!-- Post Actions -->
                    <div class="d-flex justify-content-start">
                        <button class="btn btn-light btn-sm mr-2"><i class="fa fa-thumbs-up"></i> Like</button>
                        <button class="btn btn-light btn-sm mr-2" onclick="toggleComments({{ $post->id }})">
                            <i class="fa fa-comment"></i> Comment ({{ isset($comments[$post->id]) ? count($comments[$post->id]) : 0 }})
                        </button>
                    </div>

                    {{-- Comments Section --}}
                    <div>
                        <x-comment-section :post="$post" :comments="$comments" />
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $posts->links('pagination::bootstrap-4') }}
        </div>
    </div>

    {{-- Floating Action Button --}}
    <x-floating-button />

    <script>
        const update_post = (postId, title, content, image) => {
            document.getElementById("title").value = title;
            document.getElementById("content").value = content;
            if (image) {
                document.getElementById("previewImage").src = `/storage/post/${image}`;
            }

            $("#updateModalForm").off("submit").on("submit", function (e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: `/posts/${postId}`,
                    method:"POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success:function(data){
                        if (data.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 3000
                            }).then((result) => {
                                window.location.reload();
                            });
                        }else if(data.info){
                            Swal.fire({
                                title: 'Info',
                                text: data.message,
                                icon: 'info',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    },
                    error: function (error) {
                        console.error('Error update event:', error);
                        Swal.fire({
                            title: "Error!",
                            text: "An error occurred while updating the event.",
                            icon: "error"
                        });
                    }
                });
            });
        }

        const delete_post = (postId) => {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/posts/${postId}`,
                        method: "POST",
                        data: {
                            '_token': '{{ csrf_token() }}',
                            '_method': 'delete'
                        },
                        success: function(data) {
                            Swal.fire({
                                title: 'Success!',
                                text: data.message || "Post deleted successfully",
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => window.location.reload());
                        },
                        error: function(xhr) {
                            let message = 'An error occurred while deleting the post.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                title: "Info!",
                                text: message,
                                icon: "info",
                                showConfirmButton: false,
                                timer: 4000
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
