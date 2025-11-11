<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Post;
use App\Models\Listing;

class LikeController extends Controller
{
    public function toggleLike(Request $request)
    {
        $request->validate([
            'type' => 'required|in:post,listing',
            'id' => 'required|integer',
        ]);

        $user = auth()->user();

        $likeableType = $request->type === 'post' ? Post::class : Listing::class;

        // Ensure the item exists
        $likeableItem = $likeableType::find($request->id);
        if (!$likeableItem) {
            return response()->json([
                'status' => 'error',
                'message' => ucfirst($request->type) . ' not found.',
                'errors' => ['Invalid like target.']
            ], 404);
        }

        $existingLike = Like::where('user_id', $user->id)
            ->where('likeable_type', $likeableType)
            ->where('likeable_id', $request->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            return response()->json([
                'status' => 'success',
                'liked' => false,
                'message' => ucfirst($request->type) . ' unliked.',
            ]);
        }

        Like::create([
            'user_id' => $user->id,
            'likeable_type' => $likeableType,
            'likeable_id' => $request->id,
        ]);

        if ($wasLiked) {
    // Notify the author
    $target = $likeableType::find($likeableId);
    if ($target && $target->user_id !== auth()->id()) {
        $this->notifyOnLike($target->user_id, auth()->user(), $likeableType, $likeableId);
    }
        }

        return response()->json([
            'status' => 'success',
            'liked' => true,
            'message' => ucfirst($request->type) . ' liked.',
        ]);
    }
}
