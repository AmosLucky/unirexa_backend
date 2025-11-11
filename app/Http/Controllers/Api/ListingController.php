<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\Media;

class ListingController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'condition' => 'required|in:new,used,rental,student',
            'contact_phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'media.*' => 'nullable|file|mimes:jpeg,png,jpg,mp4,mov,avi|max:20480', // Max 20MB
        ]);

        if (!$request->has('description') && !$request->hasFile('media')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Add either a description or media file to your listing.',
                'errors' => ['Empty listing content.']
            ], 422);
        }

        $listing = Listing::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'contact_phone' => $request->contact_phone,
            'location' => $request->location,
        ]);

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('uploads/listings', 'public');
                $ext = $file->getClientOriginalExtension();
                $type = in_array($ext, ['mp4', 'mov', 'avi']) ? 'video' : 'image';

                Media::create([
                    'media_url' => $path,
                    'media_type' => $type,
                    'mediable_id' => $listing->id,
                    'mediable_type' => Listing::class,
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Listing created successfully.',
            'listing' => $listing->load('media'),
        ]);
    }






















    public function getListings()
{
    $user = auth()->user();

    $listings = Listing::with('media', 'user')
        ->latest()
        ->get()
        ->map(function ($listing) use ($user) {
            $listing->like_count = $listing->likes()->count();
            $listing->share_count = $listing->shares()->count();
            $listing->isLiked = $listing->likes()->where('user_id', $user->id)->exists();
            $listing->isShared = $listing->shares()->where('user_id', $user->id)->exists();

            // Prefix media URLs
            $listing->media->transform(function ($media) {
                $media->media_url = 'https://unirexa.com/storage/' . $media->media_url;
                return $media;
            });

            return $listing;
        });

    return response()->json([
        'status' => 'success',
        'listings' => $listings,
    ]);
}


}















