<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Media;

class AuthController extends Controller
{

    /**
     * Register a new user using Firebase ID from client
     */
    public function registerUser(Request $request)
    {
        // 1. Validate input
        $validator = Validator::make($request->all(), [
            'firebase_id' => 'required|string|unique:users,firebase_id',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // 2. Create local user
            $user = User::create([
                'firebase_id' => $request->firebase_id,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'avatar' => 'https://randomuser.me/api/portraits/men/1.jpg',
                'cover_photo' => 'https://picsum.photos/800/300',
                'bio' => 'Tech enthusiast • Flutter developer • Gamer 🎮',
                'balance' => 0,
                'status' => 'active',
                'role' => 'user',
                'language' => 'English',
                'badge_level' => 'Bronze',
                'account_type' => 'user',
                'profile_setup_stage' => 1,
            ]);

            // 3. Create token for authentication (Sanctum)
            $token = $user->createToken('auth_token')->plainTextToken;

            // 4. Return full JSON including token
            return response()->json(
                [
                     'success' => true,
                    'message' => 'Registeration successful.',
                    "data"=>[
                                    'id' => $user->id,
                    'firebase_id' => $user->firebase_id,
                    'email' => $user->email,
                    'username' => $user->username,
                    'phone' => $user->phone,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'avatar' => $user->avatar,
                    'cover_photo' => $user->cover_photo,
                    'bio' => $user->bio,
                    'balance' => $user->balance,
                    'is_blocked' => $user->is_blocked,
                    'status' => $user->status,
                    'role' => $user->role,
                    'email_verified_at' => $user->email_verified_at,
                    'device_token' => $user->device_token,
                    'last_seen' => now(),
                    'token' => $token,
                    'dob' => $user->dob,
                    'followers_count' => 0,
                    'following_count' => 0,
                    'likes_count' => 0,
                    'comments_count' => 0,
                    'shares_count' => 0,
                    'is_verified' => $user->is_verified ?? false,
                    'total_spent' => $user->total_spent ?? 0,
                    'total_earned' => $user->total_earned ?? 0,
                    'last_login_at' => now(),
                    'language' => $user->language,
                    'notification_token' => $user->notification_token,
                    'ip_address' => $request->ip(),
                    'login_attempts' => $user->login_attempts ?? 0,
                    'referral_code' => 'UNX-' . strtoupper($user->username) . now()->year,
                    'referred_by' => $user->referred_by ?? null,
                    'badge_level' => $user->badge_level,
                    'account_type' => $user->account_type,
                    'interest_tags' => $user->interest_tags ?? [],
                    'post_count' => 0,
                    'gender' => $user->gender,
                    'profile_setup_stage' => $user->profile_setup_stage,
                    'location' => $user->location,
                    'website' => $user->website,
                    'university' => $user->university,
                    'faculty' => $user->faculty,
                    'department' => $user->department,
                    'interest' => $user->interest,
                    'followers' => [],
                    'is_followed_by_current_user' => false,
                    'is_following' => false,
                    'is_blocked' => false

            ]], 201);

        } catch (Exception $e) {
            // Handle duplicate firebase_id gracefully
            if (str_contains($e->getMessage(), 'firebase_id')) {
                return response()->json([
                    'success' => false,
                    'message' => 'A user with this Firebase ID already exists.'
                ], 409);
            }

            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }




     public function profileSetup(Request $request)
    {
        // ✅ Validate input
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'dob' => 'required|date',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        try {
            // ✅ Get user
            $user = User::find($request->user_id);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.',
                ], 404);
            }

            // ✅ Handle image upload
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $fileName = 'avatar_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/avatars'), $fileName);

                // Use your app domain for full URL
                $avatarUrl = url('uploads/avatars/' . $fileName);

                $user->avatar = $avatarUrl;
                 $user->avatar = $user->avatar 
    ? url('files/' . $user->avatar)
    : null;
            }

            // ✅ Update other fields
            $user->dob = $request->dob;
            $user->bio = $request->bio;
             $user->profile_setup_stage = 3;
            $user->save();
            $user->avatar = "https://blueviolet-hare-604672.hostingersite.com/".$user['avatar'];

            // ✅ Return updated user info
            return response()->json([
                'success' => true,
                'message' => 'Profile setup completed successfully!',
                'data' => $user,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }




    public function verifyEmail(Request $request)
{
    // Validate input
    $validator= Validator::make($request->all(),[
        'email' => 'required|email',
        'token' => 'required|string', // e.g., email verification token
    ]);

    try {
        // Find user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }

        // Here you should verify token
        // For example, if you stored token in DB when sending email:
        // if ($user->verification_token !== $request->token) { ... }

        // For simplicity, let's assume token is valid
        $user->email_verified_at = now();
        $user->profile_setup_stage = 3;
        $user->save();

        // Generate new auth token (Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully!',
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'verified' => true,
                'token' => $token,
            ]
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to verify email: ' . $e->getMessage()
        ], 500);
    }
}


public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input data!',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email or password!',
                ], 200);
            }

            // Generate API token
            $token = $user->createToken('auth_token')->plainTextToken;
            $user->token = $token;

            // Collect user-related stats
            $user->post_count = Post::where('user_id', $user->id)->count();
            $user->followers_count = 0;//Follower::where('followed_id', $user->id)->count();
            $user->following_count = 0;//Follower::where('follower_id', $user->id)->count();
            $user->likes_count = 0;//Like::where('user_id', $user->id)->count();
            $user->comments_count = Comment::where('user_id', $user->id)->count();
            $user->shares_count = 0;//Share::where('user_id', $user->id)->count();
             
             $user->token2 = $token;//Share::where('user_id', $user->id)->count();
              $user->avatar = $user->avatar 
    ? url('files/' . $user->avatar)
    : null;

            // Followers IDs
            $user->followers = [];//Follower::where('followed_id', $user->id)->pluck('follower_id');

            // Optional flags (for consistency)
            $user->is_followed_by_current_user = false;
            $user->is_following = false;

            return response()->json([
                'success' => true,
                'message' => 'Login successful!',
                'data' => $user,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed: ' . $e->getMessage(),
            ], 500);
        }
    }



    
public function loginByUserId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            
        ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Invalid input data!',
        //         'errors' => $validator->errors(),
        //     ], 422);
        // }

        try {
            $user = User::where('id', $request->user_id)->first();

           

            // Generate API token
            $token = $user->createToken('auth_token')->plainTextToken;
            $user->token = $token;
            $user->avatar = $user->avatar 
    ? url('files/' . $user->avatar)
    : null;

            // Collect user-related stats
            $user->post_count = Post::where('user_id', $user->id)->count();
            $user->followers_count = 0;//Follower::where('followed_id', $user->id)->count();
            $user->following_count = 0;//Follower::where('follower_id', $user->id)->count();
            $user->likes_count = 0;//Like::where('user_id', $user->id)->count();
            $user->comments_count = Comment::where('user_id', $user->id)->count();
            $user->shares_count = 0;//Share::where('user_id', $user->id)->count();
             
             $user->token2 = $token;//Share::where('user_id', $user->id)->count();

            // Followers IDs
            $user->followers = [];//Follower::where('followed_id', $user->id)->pluck('follower_id');

            // Optional flags (for consistency)
            $user->is_followed_by_current_user = false;
            $user->is_following = false;

            return response()->json([
                'success' => true,
                'message' => 'Login successful!',
                'data' => $user,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed: ' . $e->getMessage(),
            ], 500);
        }
    }


// public function login(Request $request)
//     {
//         $validator= Validator::make($request->all(),[
//             'email' => 'required|string|email',
//             'password' => 'required|string|min:6',
//         ]);

//         try {
//             $user = User::where('email', $request->email)->first();
           

//             if (!$user || !Hash::check($request->password, $user->password)) {
//                 return response()->json([
//                     'success' => false,
//                     'message' => 'Invalid email or password!',
//                 ], 401);
//             }

//             // Generate token (if you’re using Sanctum or JWT)
//             $token = $user->createToken('auth_token')->plainTextToken;

//             // Add token to user response
//             $user->token = $token;

//             return response()->json([
//                 'success' => true,
//                 'message' => 'Login successful!',
//                 'data' => $user,
//             ], 200);
//         } catch (\Exception $e) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Login failed: ' . $e->getMessage(),
//             ], 500);
//         }
//     }

}
