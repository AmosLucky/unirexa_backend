<?php

namespace App\Http\Controllers;


use App\Models\Notification;
use App\Models\User;
use App\Helpers\FirebaseHelper;

class NotificationController extends Controller
{
    public function notifyOnComment($targetUserId, $commenter, $commentText, $notifiableType, $notifiableId)
{
    // Save Notification
    Notification::create([
        'user_id' => $targetUserId,
        'type' => 'new_comment',
        'title' => "{$commenter->name} commented",
        'body' => $commentText,
        'notifiable_type' => $notifiableType,
        'notifiable_id' => $notifiableId,
        'metadata' => [
            'route' => 'post',
            'id' => $notifiableId,
            'actor' => [
                'id' => $commenter->id,
                'name' => $commenter->name,
                'avatar' => $commenter->avatar ? 'https://unirexa.com/storage/' . $commenter->avatar : null,
            ],
        ],
    ]);

    // Send Firebase Push
    $user = User::find($targetUserId);
    if ($user && $user->device_token) {
        FirebaseHelper::sendNotification(
            $user->device_token,
            "{$commenter->name} commented",
            $commentText,
            ['route' => 'post', 'id' => $notifiableId]
        );
    }
}



public function notifyOnLike($authorId, $liker, $notifiableType, $notifiableId)
{
    // Save to DB
    Notification::create([
        'user_id' => $authorId,
        'type' => 'new_like',
        'title' => "{$liker->name} liked your " . ($notifiableType === 'App\\Models\\Post' ? 'post' : 'listing'),
        'body' => '',
        'notifiable_type' => $notifiableType,
        'notifiable_id' => $notifiableId,
        'metadata' => [
            'route' => $notifiableType === 'App\\Models\\Post' ? 'post' : 'listing',
            'id' => $notifiableId,
            'actor' => [
                'id' => $liker->id,
                'name' => $liker->name,
                'avatar' => $liker->avatar ? 'https://unirexa.com/storage/' . $liker->avatar : null,
            ],
        ],
    ]);

    // Push to Firebase
    $user = \App\Models\User::find($authorId);
    if ($user && $user->device_token) {
        \App\Helpers\FirebaseHelper::sendNotification(
            $user->device_token,
            "{$liker->name} liked your " . ($notifiableType === 'App\\Models\\Post' ? 'post' : 'listing'),
            '',
            [
                'route' => $notifiableType === 'App\\Models\\Post' ? 'post' : 'listing',
                'id' => $notifiableId
            ]
        );
    }
}









public function notifyAllUsersOnPost($post, $author)
{
    $users = \App\Models\User::where('id', '!=', $author->id)->get();

    foreach ($users as $user) {
        // Create DB Notification
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'type' => 'new_post',
            'title' => "{$author->name} posted something new",
            'body' => Str::limit($post->content, 100),
            'notifiable_type' => 'App\Models\Post',
            'notifiable_id' => $post->id,
            'metadata' => [
                'route' => 'post',
                'id' => $post->id,
                'actor' => [
                    'id' => $author->id,
                    'name' => $author->name,
                    'avatar' => $author->avatar ? 'https://unirexa.com/storage/' . $author->avatar : null,
                ],
            ],
        ]);

        // Send Firebase Push
        if ($user->device_token) {
            \App\Helpers\FirebaseHelper::sendNotification(
                $user->device_token,
                "{$author->name} posted something new",
                Str::limit($post->content, 100),
                [
                    'route' => 'post',
                    'id' => $post->id,
                ]
            );
        }
    }
}




}
