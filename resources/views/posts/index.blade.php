@extends('layouts.app')

@section('content')
    @include('posts.partials.create_post_modal')
    @include('posts.partials.update_post_modal')

    <div class="container">
        @if($posts->count())
            <h3 class="mb-4">Home</h3>
        @endif

        @forelse($posts as $post)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <!-- Post Header -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <!-- Left: Avatar + User Info -->
                        <div class="d-flex align-items-center">
                            <!-- User avatar -->
                            <x-avatar avatar="{{ $post->avatar }}" name="{{ $post->name }}" />

                            <!-- Name & timestamp -->
                            <div class="ml-2">
                                <h6 class="mb-0">{{ ucwords($post->name) }}</h6>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}</small>
                            </div>
                        </div>

                        <!-- Right: Actions (optional) -->
                        @if ($post->userId == Auth::id())
                            <x-action-button :post="$post" />
                        @endif
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
        @empty
            <div class="text-center mt-5">
                <h5>No posts available. Be the first to create one!</h5>
            </div>
        @endforelse

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $posts->links('pagination::bootstrap-4') }}
        </div>
    </div>

    {{-- Floating Action Button --}}
    <x-floating-button />
@endsection
