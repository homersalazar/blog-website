<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // Home page - all posts
    public function index(Request $request)
    {
        $posts = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->orderBy('posts.created_at', 'desc')
            ->select('posts.*', 'users.id as userId', 'users.name', 'users.avatar')
            ->paginate(10);

        $postIds = collect($posts->items())->pluck('id')->toArray();

        $comments = DB::table('comments')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->whereIn('comments.post_id', $postIds)
            ->select('comments.*', 'users.name as user_name', 'users.avatar as user_avatar')
            ->orderBy('comments.created_at', 'asc')
            ->get()
            ->groupBy('post_id');

        return view('posts.index', compact('posts', 'comments'));
    }

    public function myPosts()
    {
        $userId = Auth::id();
        // Get paginated posts
        $posts = DB::table('posts')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $postIds = collect($posts->items())->pluck('id')->toArray();

        // Fetch comments with user names for those posts
        $comments = DB::table('comments')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->whereIn('comments.post_id', $postIds)
            ->select('comments.*', 'users.name as user_name', 'users.id as user_id', 'users.avatar as user_avatar')
            ->orderBy('comments.created_at', 'asc')
            ->get()
            ->groupBy('post_id');
        return view('posts.my-posts', compact('posts', 'comments'));
    }

    public function store(StorePostRequest $request)
    {
        DB::beginTransaction();

        try {
            $filename = null;

            if ($request->hasFile('image')) {
                $uploadedFile = $request->file('image');
                $filename = Auth::id() . '_' . time() . '.' . $uploadedFile->extension();
                $uploadedFile->storeAs('public/post', $filename);
            }

            Post::create([
                'title' => $request->title,
                'content' => $request->content,
                'image' => $filename,
                'user_id' => auth()->id(),
            ]);

            DB::commit();
            return redirect()->route('posts.my')->with('success', 'Post created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('store post error: ', ['exception' => $e]);
            return redirect()->back()->withErrors([
                'error' => 'Something went wrong. Please try again later.'
            ])->withInput();
        }
    }

    public function update(UpdatePostRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $post = Post::findOrFail($id);

            // Ensure the authenticated user is the owner of the post
            $this->authorize('update', $post);


            $data = [
                'title' => $request->title,
                'content' => $request->content,
            ];

            // Handle image upload
            if ($request->hasFile('image')) {

                if ($post->image) {
                    Storage::delete('public/post/' . $post->image);
                }

                $imageName = auth()->id() . '_' . time() . '.' . $request->image->extension();
                $request->image->storeAs('public/post', $imageName);

                $data['image'] = $imageName;
            }

            $post->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Post updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('update post error: ', ['exception' => $e]);

            return response()->json([
                'error' => true,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $post = Post::findOrFail($id);

            // Ensure the authenticated user is the owner of the post
            $this->authorize('delete', $post);


            // Delete the post image if it exists
            if ($post->image) {
                Storage::delete('public/post/' . $post->image);
            }

            $post->delete();

            DB::commit();
            return response()->json(['success' => 'Post deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('destroy post error: ', ['exception' => $e]);
            return redirect()->back()->withErrors([
                'error' => 'Something went wrong. Please try again later.'
            ])->withInput();
        }
    }
}
