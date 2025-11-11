<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Get comments for a specific post
     */
    public function getPostComments($postId)
    {
        $comments = Comment::with('user')
            ->where('commentable_type', 'App\Models\Post')
            ->where('commentable_id', $postId)
            ->latest()
            ->get()
            ->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'user' => [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name,
                        'username' => $comment->user->username,
                        'avatar' => $comment->user->avatar ? 'https://unirexa.com/' . $comment->user->avatar : null,
                    ],
                    'comment' => $comment->comment,
                    'created_at' => $comment->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'status' => 'success',
            'comments' => $comments
        ]);
    }













public function createComment(Request $request)
{
    $validator = Validator::make($request->all(), [
        'type' => 'required|in:post,listing',
        'id' => 'required|integer',
        'comment' => 'required|string|max:1000',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => $validator->errors()->first(),
            'errors' => $validator->errors()->all()
        ], 422);
    }

    $commentableType = $request->type === 'post' ? \App\Models\Post::class : \App\Models\Listing::class;
    $commentable = $commentableType::findOrFail($request->id);

    $comment = Comment::create([
        'user_id' => auth()->id(),
        'comment' => $request->comment,
        'commentable_id' => $commentable->id,
        'commentable_type' => $commentableType
    ]);

    // 🔔 Notify the author
    if ($commentable->user_id !== auth()->id()) {
        Notification::create([
            'user_id' => $commentable->user_id,
            'type' => 'new_comment',
            'title' => auth()->user()->name . ' commented on your ' . $request->type,
            'body' => $request->comment,
            'notifiable_type' => $commentableType,
            'notifiable_id' => $commentable->id,
            'metadata' => [
                'route' => $request->type,
                'id' => $commentable->id,
                'actor' => [
                    'id' => auth()->id(),
                    'name' => auth()->user()->name,
                    'avatar' => auth()->user()->avatar ? 'https://unirexa.com/storage/' . auth()->user()->avatar : null,
                ],
            ]
        ]);

        if ($commentable->user->device_token) {
            \App\Helpers\FirebaseHelper::sendNotification(
                $commentable->user->device_token,
                auth()->user()->name . ' commented on your ' . $request->type,
                $request->comment,
                [
                    'route' => $request->type,
                    'id' => $commentable->id
                ]
            );
        }
    }

    return response()->json([
        'message' => 'Comment added',
        'data' => $comment
    ]);
}



}
