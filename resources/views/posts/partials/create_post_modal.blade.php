<!-- Create Post Modal -->
<div class="modal fade" id="createPostModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Create Post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <!-- Title -->
                    <div class="form-group">
                        <label>Title</label>
                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            required>
                    </div>

                    <!-- Content -->
                    <div class="form-group">
                        <label>Content</label>
                        <textarea
                            name="content"
                            rows="5"
                            class="form-control"
                            required>
                        </textarea>
                    </div>

                    <!-- Image -->
                    <div class="form-group">
                        <label>Image (optional)</label>
                        <input
                            type="file"
                            name="image"
                            class="form-control-file"
                            accept="image/*">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-teal-600">Post</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
