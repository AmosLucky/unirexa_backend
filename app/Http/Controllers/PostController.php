<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


public function getAllPosts()
{
    $user = Auth::user();
    if($user == null){
        return response()->json([
        'success' => true,
        'message' => 'Post created successfully',
        ]);
        
    }

    $posts = Post::with(['media', 'user'])
        ->withCount(['likes', 'comments', 'shares'])
        ->latest()
        ->paginate(10); // ✅ pagination added

    $formattedPosts = $posts->getCollection()->map(function ($post) use ($user) {

        return [
            'id' => $post->id,
            'content' => $post->content,

            // ✅ Media
            'media' => $post->media->map(function ($media) {
                return [
                    'type' => $media->type,
                    'url' => url('storage/' . $media->file_path),
                ];
            }),

            'created_at' => $post->created_at->toISOString(),

            // ✅ Counts (optimized)
            'likes_count' => $post->likes_count,
            'comments_count' => $post->comments_count,
            'shares_count' => $post->shares_count,
            "is_follow"=> true,

            // ✅ Example logic (adjust later)
            // 'is_follow' => $user->following()
            //     ->where('following_id', $post->user_id)
            //     ->exists(),

            'has_seen' => false, // you can implement view tracking later

            // ✅ Author
            'author' => [
                'id' => $post->user->id,
                'username' => $post->user->username,
                'avatar' => $post->user->image
                    ? url('storage/' . $post->user->image)
                    : null,
            ],
        ];
    });

    return response()->json([
        'success' => true,
        'message' => 'Posts fetched successfully',
        'data' => $formattedPosts,

        // ✅ Pagination meta (important for frontend)
        'pagination' => [
            'current_page' => $posts->currentPage(),
            'last_page' => $posts->lastPage(),
            'per_page' => $posts->perPage(),
            'total' => $posts->total(),
        ]
    ]);
}

    /**
     * Show the form for creating a new resource.
     */

public function createPost(Request $request)
{
    $user = auth()->user();

    // ✅ Validate input
    $request->validate([
        'content' => 'nullable|string|max:1000',
        'media.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:10240'
    ]);

    DB::transaction(function () use ($request, $user, &$post) {

        // ✅ Create post
        $post = Post::create([
            'user_id' => $user->id,
            'content' => $request->content,
        ]);

        // ✅ Handle media uploads
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {

                $path = $file->store('posts', 'public');

                // detect type
                $type = str_contains($file->getMimeType(), 'video') ? 'video' : 'image';

                PostMedia::create([
                    'post_id' => $post->id,
                    'type' => $type,
                    'file_path' => $path,
                ]);
            }
        }
    });

    // reload with relationships
    $post->load(['media', 'user']);

    return response()->json([
        'success' => true,
        'message' => 'Post created successfully',
        'data' => [
            'id' => $post->id,
            'content' => $post->content,

            'media' => $post->media->map(function ($media) {
                return [
                    'type' => $media->type,
                    'url' => url('storage/' . $media->file_path),
                ];
            }),

            'created_at' => $post->created_at->toISOString(),

            'likes_count' => 0,
            'comments_count' => 0,
            'shares_count' => 0,

            'is_follow' => false,
            'has_seen' => false,

            'author' => [
                'id' => $post->user->id,
                'username' => $post->user->username,
                'avatar' => $post->user->image
                    ? url('storage/' . $post->user->image)
                    : null,
            ],
        ]
    ]);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
