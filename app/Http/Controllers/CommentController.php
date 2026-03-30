<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        DB::beginTransaction();

        try {

            DB::table('comments')->insert([
                'user_id'    => Auth::id(),
                'post_id'    => $postId,
                'comment'    => $request->comment,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $commentId = DB::getPdo()->lastInsertId();

            DB::commit();
            return response()->json([
                'success'    => true,
                'comment_id' => $commentId,           // 👈 needed for id="comment-{id}"
                'comment'    => $request->comment,    // 👈 needed for display
                'user_name'  => ucwords(Auth::user()->name), // 👈 needed for display
                'created_at' => 'just now',           // 👈 needed for timestamp
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
        $comment = DB::table('comments')->where('id', $id)->first();
        DB::beginTransaction();

        try {

            if (!$comment) {
                return response()->json(['message' => 'Comment not found.'], 404);
            }

            if ($comment->user_id !== Auth::id()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }

            DB::table('comments')->where('id', $id)->delete();

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
