@props([
    'post' => [],
    'comments' => []
])

<div id="comments-section-{{ $post->id }}" style="display:none;" class="mt-3">
    <hr>

    {{-- Existing Comments --}}
    <div id="comments-list-{{ $post->id }}">
        @if(isset($comments[$post->id]))
            @foreach($comments[$post->id] as $comment)
                <div class="d-flex mb-2" id="comment-{{ $comment->id }}">
                    <div class="mr-2">
                        <x-avatar avatar="{{ $comment->user_avatar }}" name="{{ $comment->user_name }}" size="30" />
                    </div>
                    <div class="flex-grow-1">
                        <div class="bg-light rounded p-2">
                            <strong>{{ ucwords($comment->user_name) }}</strong>
                            <p class="mb-0">{{ $comment->comment }}</p>
                        </div>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</small>
                        @if($comment->user_id == Auth::id())
                            <small class="text-danger ml-2" style="cursor:pointer"
                                onclick="deleteComment({{ $comment->id }}, {{ $post->id }})">Delete</small>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Add Comment Input --}}
    @auth
        <div class="d-flex mt-2">
            <div class="mr-2">
                <x-avatar avatar="{{ Auth::user()->avatar }}" name="{{ Auth::user()->name }}" size="40" />
            </div>
            <div class="flex-grow-1">
                <div class="input-group">
                    <input type="text"
                        id="comment-input-{{ $post->id }}"
                        class="form-control form-control-sm"
                        placeholder="Write a comment...">
                    <div class="input-group-append">
                        <button class="btn btn-teal-600 btn-sm"
                            onclick="submitComment({{ $post->id }})">Post</button>
                    </div>
                </div>
            </div>
        </div>
    @endauth
</div>

<script>
    const toggleComments = (postId) => {
            const section = document.getElementById(`comments-section-${postId}`);
            section.style.display = section.style.display === 'none' ? 'block' : 'none';
        }

        const submitComment = (postId) => {
            const input = document.getElementById(`comment-input-${postId}`);
            const comment = input.value.trim();
            if (!comment) return;

            $.ajax({
                url: `/posts/${postId}/comments`,
                method: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'comment': comment
                },
                success: function(data) {
                    if (data.success) {
                        const commentHtml = `
                            <div class="d-flex mb-2" id="comment-${data.comment_id}">
                                <div class="mr-2"></div>
                                <div class="flex-grow-1">
                                    <div class="bg-light rounded p-2">
                                        <strong>${data.user_name}</strong>
                                        <p class="mb-0">${data.comment}</p>
                                    </div>
                                    <small class="text-muted">${data.created_at}</small>
                                    <small class="text-danger ml-2" style="cursor:pointer"
                                        onclick="deleteComment(${data.comment_id}, ${postId})">Delete</small>
                                </div>
                            </div>`;
                        document.getElementById(`comments-list-${postId}`).insertAdjacentHTML('beforeend', commentHtml);
                        input.value = '';
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Could not post comment.', 'error');
                }
            });
        }

        const deleteComment = (commentId, postId) => {
            Swal.fire({
                title: 'Delete comment?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/comments/${commentId}`,
                        method: 'POST',
                        data: { '_token': '{{ csrf_token() }}', '_method': 'DELETE' },
                        success: function(data) {
                            document.getElementById(`comment-${commentId}`).remove();
                            Swal.fire({ title: 'Deleted!', icon: 'success', timer: 1500, showConfirmButton: false });
                        }
                    });
                }
            });
        }
</script>
