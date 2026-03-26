<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class CommentController extends Controller
{
    /**
     * Get comments for a specific post
     */
    public function getComments(Request $request)
{
    $validator = Validator::make($request->all(), [
        'commentable_id' => 'required|integer',
        'commentable_type' => 'required|string|in:post,reel',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => $validator->errors()->first(),
            'errors' => $validator->errors()->all(),
        ], 422);
    }

    $map = [
        'post' => \App\Models\Post::class,
        'reel' => \App\Models\Reel::class,
    ];

    $type = $request->commentable_type;

    if (!isset($map[$type])) {
        return response()->json([
            'message' => 'Invalid comment type'
        ], 422);
    }

    $modelClass = $map[$type];

    // 🔥 ensure parent exists
    $commentable = $modelClass::findOrFail($request->commentable_id);

    // 💬 fetch comments
    $comments = \App\Models\Comment::with('user')
        ->where('commentable_id', $commentable->id)
        ->where('commentable_type', $modelClass)
        ->latest()
        ->get();

    // 🔥 format response
    $formatted = $comments->map(function ($comment) {
        return [
            'id' => $comment->id,
            'comment' => $comment->content,
            'created_at' => $comment->created_at->toISOString(),
            'username' => $comment->user->username,
             'user_id' => $comment->user->id,
             'avatar' => $comment->user->avatar
                    ? url('storage/' . $comment->user->avatar)
                    : null,
            "created_at" =>$comment->created_at,
            'post_id' =>  $comment->commentable_id,

            'user' => [
                'id' => $comment->user->id,
                'name' => $comment->user->username,
                'avatar' => $comment->user->avatar
                    ? url('storage/' . $comment->user->avatar)
                    : null,
            ],
        ];
    });

    return response()->json([
        'success' => true,
        'message' => 'Comments fetched successfully',
        'data' => $formatted,
    ]);
}
    // public function getPostComments($postId)
    // {
    //     $comments = Comment::with('user')
    //         ->where('commentable_type', 'App\Models\Post')
    //         ->where('commentable_id', $postId)
    //         ->latest()
    //         ->get()
    //         ->map(function ($comment) {
    //             return [
    //                 'id' => $comment->id,
    //                 'user' => [
    //                     'id' => $comment->user->id,
    //                     'name' => $comment->user->name,
    //                     'username' => $comment->user->username,
    //                     'avatar' => $comment->user->avatar ? 'https://unirexa.com/' . $comment->user->avatar : null,
    //                 ],
    //                 'comment' => $comment->comment,
    //                 'created_at' => $comment->created_at->diffForHumans(),
    //             ];
    //         });

    //     return response()->json([
    //         'status' => 'success',
    //         'comments' => $comments
    //     ]);
    // }













// public function createComment(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'type' => 'required|in:post,listing',
//         'id' => 'required|integer',
//         'comment' => 'required|string|max:1000',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'message' => $validator->errors()->first(),
//             'errors' => $validator->errors()->all()
//         ], 422);
//     }

//     $commentableType = $request->type === 'post' ? \App\Models\Post::class : \App\Models\Listing::class;
//     $commentable = $commentableType::findOrFail($request->id);

//     $comment = Comment::create([
//         'user_id' => auth()->id(),
//         'comment' => $request->comment,
//         'commentable_id' => $commentable->id,
//         'commentable_type' => $commentableType
//     ]);

//     // 🔔 Notify the author
//     if ($commentable->user_id !== auth()->id()) {
//         Notification::create([
//             'user_id' => $commentable->user_id,
//             'type' => 'new_comment',
//             'title' => auth()->user()->name . ' commented on your ' . $request->type,
//             'body' => $request->comment,
//             'notifiable_type' => $commentableType,
//             'notifiable_id' => $commentable->id,
//             'metadata' => [
//                 'route' => $request->type,
//                 'id' => $commentable->id,
//                 'actor' => [
//                     'id' => auth()->id(),
//                     'name' => auth()->user()->name,
//                     'avatar' => auth()->user()->avatar ? 'https://unirexa.com/storage/' . auth()->user()->avatar : null,
//                 ],
//             ]
//         ]);

//         if ($commentable->user->device_token) {
//             \App\Helpers\FirebaseHelper::sendNotification(
//                 $commentable->user->device_token,
//                 auth()->user()->name . ' commented on your ' . $request->type,
//                 $request->comment,
//                 [
//                     'route' => $request->type,
//                     'id' => $commentable->id
//                 ]
//             );
//         }
//     }

//     return response()->json([
//         'message' => 'Comment added',
//         'data' => $comment
//     ]);
// }


  public function createComment(Request $request)
{
    $validator = Validator::make($request->all(), [
        'comment' => 'required|string|max:1000',
        'commentable_id' => 'required|integer',
        'commentable_type' => 'required|string|in:post,reel',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => $validator->errors()->first(),
            'errors' => $validator->errors()->all(),
        ], 422);
    }

    $user = auth()->user();

    // 🔥 Map type to model
    $map = [
        'post' => \App\Models\Post::class,
        'reel' => \App\Models\Reel::class,
    ];

    $type = $request->commentable_type;

    if (!isset($map[$type])) {
        return response()->json([
            'message' => 'Invalid comment type'
        ], 422);
    }

    $modelClass = $map[$type];

    $commentable = $modelClass::with('user')->findOrFail($request->commentable_id);

    // 💬 Create comment
    $comment = Comment::create([
        'user_id' => $user->id,
        'content' => $request->comment,
        'commentable_id' => $commentable->id,
        'commentable_type' => $modelClass,
        'username'=>$user->username
    ]);

    // 🔔 Notification
    // if ($commentable->user_id !== $user->id) {

    //     Notification::create([
    //         'user_id' => $commentable->user_id,
    //         'type' => 'new_comment',
    //         'title' => $user->name . ' commented on your ' . $type,
    //         'body' => $request->comment,
    //         'metadata' => [
    //             'type' => $type,
    //             'id' => $commentable->id,
    //             'actor' => [
    //                 'id' => $user->id,
    //                 'name' => $user->name,
    //                 'avatar' => $user->avatar
    //                     ? url('storage/' . $user->avatar)
    //                     : null,
    //             ],
    //         ],
    //     ]);

    //     if ($commentable->user->device_token) {
    //         \App\Helpers\FirebaseHelper::sendNotification(
    //             $commentable->user->device_token,
    //             $user->name . ' commented on your ' . $type,
    //             $request->comment,
    //             [
    //                 'type' => $type,
    //                 'id' => $commentable->id,
    //             ]
    //         );
    //     }
    // }

    return response()->json([
        'success' => true,
        'message' => 'Comment added successfully',
        'data' => $comment
    ]);
}




}
