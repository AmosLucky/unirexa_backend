<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Post;
use App\Models\Reel;

use App\Models\Listing;

class LikeController extends Controller
{


public function toggleLike(Request $request)
{
    $request->validate([
        'type' => 'required|in:post,listing,reel',
        'id' => 'required|integer',
    ]);

    $user = auth()->user();

    // ✅ Map type to model
    $typeMap = [
        'post' => Post::class,
        'reel' => Reel::class,
        'listing' => Listing::class,
    ];

    $likeableType = $typeMap[$request->type];
    $likeableId = $request->id;

    // ✅ Check if item exists
    $likeableItem = $likeableType::find($likeableId);

    if (!$likeableItem) {
        return response()->json([
            'status' => 'error',
            'message' => ucfirst($request->type) . ' not found.',
        ], 404);
    }

    // ✅ Check existing like
    $existingLike = Like::where([
        'user_id' => $user->id,
        'likeable_type' => $likeableType,
        'likeable_id' => $likeableId,
    ])->first();

    // 🔁 UNLIKE
    if ($existingLike) {
        $existingLike->delete();

        return response()->json([
            'status' => 'success',
            'liked' => false,
            'message' => ucfirst($request->type) . ' unliked.',
        ]);
    }

    // ❤️ LIKE
    $like = Like::create([
        'user_id' => $user->id,
        'likeable_type' => $likeableType,
        'likeable_id' => $likeableId,
    ]);

    // ✅ Notify ONLY when newly liked
    // if ($likeableItem->user_id !== $user->id) {
    //     $this->notifyOnLike(
    //         $likeableItem->user_id,
    //         $user,
    //         $likeableType,
    //         $likeableId
    //     );
    // }

    return response()->json([
        'status' => 'success',
        'liked' => true,
        'message' => ucfirst($request->type) . ' liked.',
    ]);
}




}
