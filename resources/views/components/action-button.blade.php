@props([
    'post' => []
])
<div class="dropdown">
    <a href="#" class="text-dark" data-toggle="dropdown" aria-expanded="false" style="text-decoration: none;">
        <i class="fa-solid fa-ellipsis fa-xl"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right">
        <div class="dropdown-item" data-toggle="modal" data-target="#updatePostModal" onclick="updatePost('{{ $post->id }}', '{{ $post->title }}', '{{ $post->content }}', '{{ $post->image }}')">Edit Post</div>
        <div class="dropdown-item" onclick="deletePost('{{ $post->id }}')">Delete Post</div>
    </div>
</div>

<script>
    const updatePost = (postId, title, content, image) => {
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

    const deletePost = (postId) => {
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
