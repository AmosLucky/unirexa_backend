<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Media;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Services\ModerationService;
use App\Models\User;



use Illuminate\Support\Str;

class PostController extends Controller
{
    // ✅ Create a new post with optional content and media
/**
     * Create a new post
     */
    public function createPost(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'content' => 'nullable|string|max:1000',
            'hashtags' => 'nullable|string',
            'files.*' => 'nullable|file|mimes:jpeg,png,jpg,mp4,mov,avi|max:20480',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        // Ensure that either content or files are present
        if (empty($request->content) && !$request->hasFile('files')) {
            return response()->json([
                'success' => false,
                'message' => 'You must provide either content or at least one file.',
            ], 400);
        }
        
    

        try {
            // Create post
            $post = Post::create([
                'user_id' => auth()->id() ?? 1 , // fallback for testing
                'content' => $request->content ?? '',
                'hashtags' => $request->hashtags ?? '',
            ]);

            $mediaFiles = [];

            // Upload media files if available
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $type = in_array($extension, ['mp4', 'mov', 'avi']) ? 'video' : 'image';
                    $path = $file->store('uploads/posts', 'public');
Media::create([
    'post_id' => $post->id,
    'media_type' => $type,
    'url' => Storage::url($path),
    'thumbnail' => $type == 'video' ? 'path/to/thumbnail.jpg' : null,
    'mediable_type' => 'App\\Models\\Post', // or whatever polymorphic type
    'mediable_id' => $post->id,
]);


                    $mediaFiles[] = [
                        'type' => $type,
                        'url' =>'https://blueviolet-hare-604672.hostingersite.com/'. Storage::url($path),
                    ];
                }
            }

            // Success response
            return response()->json([
                'success' => true,
                'message' => 'Post created successfully',
                'data' => [
                    'id' => $post->id,
                    'content' => $post->content,
                    'media' => $mediaFiles,
                    'created_at' => now()->toISOString(),
                    'likes_count' => 0,
                    'comments_count' => 0,
                    'shares_count' => 0,
                    'is_follow' => false,
                    'has_seen' => false,
                    'is_liked' => false,
                    'author' => [
                        'id' => auth()->id() ?? 105,
                        'username' => auth()->user()->username ?? 'bella_snap',
                        'avatar' => auth()->user()->avatar ?? 'https://randomuser.me/api/portraits/women/5.jpg',
                    ],
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create post: ' . $e->getMessage(),
            ], 500);
        }
    }













    public function getSinglePost($id)
{
    $user = auth()->user();

    $post = Post::with(['user', 'media'])
        ->find($id);

    if (!$post) {
        return response()->json([
            'status' => 'error',
            'message' => 'Post not found.',
            'errors' => ['Invalid post ID.']
        ], 404);
    }

    $post->like_count = $post->likes()->count();
    $post->share_count = $post->shares()->count();
    $post->isLiked = $post->likes()->where('user_id', $user->id)->exists();
    $post->isShared = $post->shares()->where('user_id', $user->id)->exists();

    // Add full media URLs
    $post->media->transform(function ($media) {
        $media->media_url = 'https://unirexa.com/storage/' . $media->media_url;
        return $media;
    });

    return response()->json([
        'status' => 'success',
        'post' => $post,
    ]);
}










public function getUserPosts($id)
{
    $posts = Post::with(['user:id,username,name,avatar', 'media'])
        ->where('user_id', $id)
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($post) {
            return [
                'id' => $post->id,
                'content' => $post->content,
                'user' => [
                    'id' => $post->user->id,
                    'username' => $post->user->username,
                    'name' => $post->user->name,
                    'avatar' => $post->user->avatar ? 'https://unirexa.com/' . $post->user->avatar : null,
                ],
                'media' => $post->media->map(function ($m) {
                    return [
                        'id' => $m->id,
                        'media_url' => 'https://unirexa.com/' . $m->media_url,
                        'media_type' => $m->media_type,
                    ];
                }),
                'likes_count' => $post->likes()->count(),
                'comments_count' => $post->comments()->count(),
                'shares_count' => $post->shares()->count(),
                'created_at' => $post->created_at->diffForHumans(),
            ];
        });

    return response()->json([
        'status' => true,
        'data' => $posts,
    ]);
}








}
