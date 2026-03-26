<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Rex;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    /**
     * Save or update the authenticated user's username.
     * Username must be unique and match allowed characters.
     */
    public function saveUsername(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'min:3',
                'max:20',
                'regex:/^[a-zA-Z0-9_]+$/', // only letters, numbers, underscores
                'unique:users,username,' . auth()->id(),
            ],
        ]);

        if ($validator->fails()) {
            // Flatten error messages into a simple array of strings
            $errors = [];
            foreach ($validator->errors()->messages() as $fieldErrors) {
                foreach ($fieldErrors as $errorMessage) {
                    $errors[] = $errorMessage;
                }
            }

            return response()->json([
                'status' => 'error',
                'message' => $errors[0] ?? 'Validation failed.',
                'errors' => $errors,
            ], 422);
        }

        $user = auth()->user();
        $user->username = $request->username;
        $user->name = $request->username;

        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Username saved successfully.',
            'user' => $user->makeHidden(['password', 'remember_token']),
        ]);
    }











public function uploadAvatar(Request $request)
{
    if (!auth()->check()) {
        return response()->json([
            'status' => 'error',
            'message' => 'User not authenticated.',
        ], 401);
    }

    $validator = Validator::make($request->all(), [
        'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048', // max 2MB
    ]);

    if ($validator->fails()) {
        $errors = [];
        foreach ($validator->errors()->messages() as $fieldErrors) {
            foreach ($fieldErrors as $errorMessage) {
                $errors[] = $errorMessage;
            }
        }

        return response()->json([
            'status' => 'error',
            'message' => $errors[0] ?? 'Validation failed.',
            'errors' => $errors,
        ], 422);
    }

    $file = $request->file('avatar');
    $filename = uniqid('avatar_') . '.' . $file->getClientOriginalExtension();
    $path = $file->storeAs('public/avatars', $filename);

    $user = auth()->user();
    $relativePath = 'storage/avatars/' . $filename;
    $user->avatar = $relativePath;
    $user->save();

    $userArray = $user->makeHidden(['password', 'remember_token'])->toArray();
    $userArray['avatar'] = $user->avatar ? 'https://unirexa.com/' . $user->avatar : null;

    return response()->json([
        'status' => 'success',
        'message' => 'Avatar uploaded successfully.',
        'user' => $userArray,
    ]);
}
















public function updateUser(Request $request)
{
    $user = auth()->user();

   $allowedFields = ['name', 'phone', 'bio', 'school', 'faculty', 'department', 'reg_number', 'admission_year'];


    $input = $request->only($allowedFields);

    // Validation rules based on allowed fields
    $rules = [
        'name'  => 'sometimes|string|max:255',
        'phone' => 'sometimes|string|max:20|unique:users,phone,' . $user->id,
        'bio'   => 'sometimes|string|max:1000',
    ];

    $validator = Validator::make($input, $rules);

    if ($validator->fails()) {
        $errors = [];
        foreach ($validator->errors()->messages() as $fieldErrors) {
            foreach ($fieldErrors as $errorMessage) {
                $errors[] = $errorMessage;
            }
        }

        return response()->json([
            'status' => 'error',
            'message' => $errors[0] ?? 'Validation failed.',
            'errors' => $errors,
        ], 422);
    }

    foreach ($input as $key => $value) {
        $user->$key = $value;
    }

    $user->save();

    $userArray = $user->makeHidden(['password', 'remember_token'])->toArray();
    if ($user->avatar) {
        $userArray['avatar'] = 'https://unirexa.com/' . $user->avatar;
    }

    return response()->json([
        'status' => 'success',
        'message' => 'User profile updated successfully.',
        'user' => $userArray,
    ]);
}















public function getProfile($id)
{
    $authUser = auth()->user();
    $user = User::find($id);

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'User not found.',
            'errors' => ['User not found.']
        ], 404);
    }

    // Stats
    $postCount = $user->posts()->count();
    $listingCount = $user->listings()->count();
    $likesReceived = \App\Models\Like::where('likeable_type', Post::class)
        ->whereIn('likeable_id', $user->posts()->pluck('id'))
        ->count();
    $rexCount = \App\Models\Rex::where('rexed_user_id', $user->id)->count();
    $hasRexed = \App\Models\Rex::where('user_id', $authUser->id)
        ->where('rexed_user_id', $user->id)
        ->exists();

   return response()->json([
    'status' => true,
    'data' => [
        'id' => $user->id,
        'username' => $user->username,
        'name' => $user->name,
        'avatar' => $user->avatar ? 'https://unirexa.com/' . $user->avatar : null,
        'bio' => $user->bio,
        'faculty' => $user->faculty,
        'department' => $user->department,
        'reg_number' => $user->reg_number,
        'admission_year' => $user->admission_year,
        'post_count' => $postCount,
        'listing_count' => $listingCount,
        'likes_received' => $likesReceived,
        'rexers_count' => $rexCount,
        'rexing_count' => $rexingCount,
        'has_rexed' => $hasRexed,
    ]
]);
}












public function getRexers($id)
{
    $rexers = Rex::with('user') // 'user' is the person who followed
        ->where('rexed_user_id', $id)
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($rex) {
            return [
                'id' => $rex->user->id,
                'username' => $rex->user->username,
                'name' => $rex->user->name,
                'avatar' => $rex->user->avatar ? 'https://unirexa.com/' . $rex->user->avatar : null,
            ];
        });

    return response()->json([
        'status' => true,
        'data' => $rexers
    ]);
}


public function setupProfile(Request $request)
{
    $user = auth()->user();

    // ✅ Validate request
    $request->validate([
        'date_of_birth' => 'required|date',
        'bio' => 'required|string|max:500',
        'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // ✅ Store image
    $path = $request->file('image')->store('profiles', 'public');

    // ✅ Generate full URL
    $imageUrl = asset('storage/' . $path);

    // ✅ Save user profile (adjust fields as needed)
    $user->update([
        'date_of_birth' => $request->date_of_birth,
        'bio' => $request->bio,
        'image' => $path, // store relative path in DB
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Profile setup successful',
        'image_url' => $imageUrl, 
        'data' => [
            'date_of_birth' => $user->date_of_birth,
            'bio' => $user->bio,
            'image_url' => $imageUrl, // ✅ full path returned
        ]
    ]);
}







}
