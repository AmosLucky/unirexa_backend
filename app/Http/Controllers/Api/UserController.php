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
    $user = User::find($id);

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'User not found.',
            'errors' => ['User not found.']
        ], 404);
    }

    return response()->json([
        'status' => true,
        'data' => [
            "id" => $user->id,

            "firebase_id" => $user->firebase_id ?? "N/A",
            "email" => $user->email ?? "user{$user->id}@example.com",
            "username" => $user->username ?? "user_{$user->id}",
            "phone" => $user->phone ?? "+2348000000000",

            "first_name" => $user->first_name ?? "John",
            "last_name" => $user->last_name ?? "Doe",

            "avatar" => $user->avatar 
                ? url($user->avatar) 
                : "https://randomuser.me/api/portraits/men/" . ($user->id % 50) . ".jpg",

            "cover_photo" => $user->cover_photo 
                ?? "https://picsum.photos/800/300?random={$user->id}",

            "bio" => $user->bio 
                ?? "Hey 👋 I'm using the app",

            "balance" => $user->balance ?? rand(0, 1000),

            "is_blocked" => $user->is_blocked ?? false,
            "status" => $user->status ?? "active",
            "role" => $user->role ?? "user",

            "email_verified_at" => $user->email_verified_at ?? now()->toISOString(),
            "device_token" => $user->device_token ?? "device_token_" . uniqid(),

            "last_seen" => $user->last_seen ?? now()->toISOString(),
            "token" => "token_" . uniqid(),

            "dob" => $user->dob ?? "2000-01-01",

            "followers_count" => rand(0, 5),
            "following_count" => rand(0, 5),

            "likes_count" => rand(0, 2),
            "comments_count" => rand(0, 5),
            "shares_count" => rand(0, 20),

            "is_verified" => $user->is_verified ?? (bool)rand(0,1),

            "total_spent" => $user->total_spent ?? rand(0, 5),
            "total_earned" => $user->total_earned ?? rand(0, 10),

            "last_login_at" => $user->last_login_at ?? now()->subDays(rand(0,5))->toISOString(),

            "language" => $user->language ?? "English",

            "notification_token" => $user->notification_token ?? "notif_" . uniqid(),
            "ip_address" => $user->ip_address ?? "127.0.0.1",
            "login_attempts" => $user->login_attempts ?? rand(0,3),

            "referral_code" => $user->referral_code ?? "REF-" . strtoupper(substr(md5($user->id), 0, 6)),
            "referred_by" => $user->referred_by ?? "",

            "badge_level" => $user->badge_level ?? ["Bronze", "Silver", "Gold"][rand(0,2)],
            "account_type" => $user->account_type ?? "user",

            "interest_tags" => $user->interest_tags
                ? json_decode($user->interest_tags, true)
                : ["tech", "music", "sports"],

            "post_count" => rand(0, 1),

            "gender" => $user->gender ?? (rand(0,1) ? "male" : "female"),

            "profile_setup_stage" => $user->profile_setup_stage ?? rand(1, 3),

            "location" => $user->location ?? "Lagos, Nigeria",
            "website" => $user->website ?? "https://example.com",

            "university" => $user->university ?? "University of Lagos",
            "faculty" => $user->faculty ?? "Engineering",
            "department" => $user->department ?? "Computer Science",

            "interest" => $user->interest ?? "Tech, music and gaming",

            "followers" => [],

            "is_followed_by_current_user" => false,
            "is_following" => false,
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
