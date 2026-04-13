<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;


use App\Models\Reel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;




class ReelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
            public function createReel(Request $request)
{
    $validator = Validator::make($request->all(), [
        'caption' => 'nullable|string|max:1000',
        'hashtags' => 'nullable|string',
        'video' => 'required|file|mimes:mp4,mov,avi,webm|max:20000',
        'allow_comment' => 'nullable|boolean',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => $validator->errors()->first(),
            'errors' => $validator->errors()->all(),
        ], 422);
    }

    $user = auth()->user();

    DB::beginTransaction();

    try {

        // 🎬 Upload video
        $videoPath = $request->file('video')->store('reels', 'public');

        // 🧠 Create reel
        $reel = Reel::create([
            'user_id' => $user->id,
            'caption' => $request->caption,
            'hashtags' => $request->hashtags,
            'video_url' => $videoPath,
            'allow_comment' => $request->allow_comment ?? true,
            'like_count' => 0,
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Reel created successfully',
            'data' => [
                'id' => $reel->id,
                'caption' => $reel->caption,
                'hashtags' => $reel->hashtags,
                'video_url' => url('storage/' . $reel->video_url),
                'allow_comment' => $reel->allow_comment,
                'like_count' => $reel->like_count,
                "comment_count" => 12,
    "share_count" => 0,
    "isLiked" => false,
    "is_seen"  => false,
    "author" => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'avatar' => $user->avatar
                        ? url('storage/' . $user->avatar)
                        : null,
                ],
                'created_at' => $reel->created_at->toISOString(),
            ]
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'message' => 'Failed to create reel',
            'error' => $e->getMessage(),
        ], 500);
    }
}




public function getReels()
{
    $user = auth()->user();

    $reels = Reel::with('user')
        ->withCount('likes') // ✅ real like count
        ->with(['likes' => function ($query) use ($user) {
            $query->where('user_id', $user->id); // ✅ current user like
        }])
        ->latest()
        ->get();

    $formatted = $reels->map(function ($reel) use ($user) {

        return [
            'id' => $reel->id,

            // 🎬 video
            'video_url' => $reel->video_url
                ? url('files/' . $reel->video_url)
                : null,

            // ✍️ content
            'caption' => $reel->caption,

            // #️⃣ hashtags
            'hashtags' => $reel->hashtags
                ? array_values(array_filter(explode(' ', $reel->hashtags)))
                : [],

            'allow_comment' => (bool) $reel->allow_comment,

            // ❤️ REAL stats
            'like_count' => $reel->likes_count, // ✅ FIXED
            'comment_count' => $reel->comments()->count(),
            'share_count' => (int) ($reel->share_count ?? 0),

            // ✅ REAL user state
            'is_liked' => $reel->likes->isNotEmpty(),

            'is_seen' => false,

            // ⏱ time
            'created_at' => $reel->created_at->toISOString(),

            // 👤 author
            'author' => [
                'id' => $reel->user->id,
                'username' => $reel->user->username ?? $reel->user->name,
                'avatar' => $reel->user->avatar
                    ? url('files/' . $reel->user->avatar)
                    : null,
            ],
        ];
    });

    return response()->json([
        'success' => true,
        'message' => 'Reels fetched successfully',
        'data' => $formatted,
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
    public function show(Reel $reel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reel $reel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reel $reel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reel $reel)
    {
        //
    }
}