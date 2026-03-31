<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, $postId)
    {
        DB::beginTransaction();

        try {
            $comment = Comment::create([
                'user_id'    => Auth::id(),
                'post_id'    => $postId,
                'comment'    => $request->comment,
            ]);

            $commentId = $comment->id;

            DB::commit();
            return response()->json([
                'success'    => true,
                'comment_id' => $commentId,
                'comment'    => $request->comment,
                'user_name'  => ucwords(Auth::user()->name),
                'created_at' => 'just now',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('create comment error: ', ['exception' => $e]);

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
            $comment = Comment::findOrFail($id);

            $this->authorize('delete', $comment);

            if (!$comment) {
                return response()->json(['message' => 'Comment not found.'], 404);
            }

            $comment->delete();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Comment deleted.']);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('delete comment error: ', ['exception' => $e]);

            return response()->json([
                'error' => true,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }
}
