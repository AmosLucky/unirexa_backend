<?php
namespace App\Http\Controllers;

use App\Models\Rex;
use App\Models\User;
use App\Models\Notification;
use App\Helpers\FirebaseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RexController extends Controller
{
    public function rex($id)
    {
        $rexer = Auth::user();

        if ($rexer->id == $id) {
            return response()->json(['message' => 'You cannot rex yourself.'], 400);
        }

        $existing = Rex::where('rexer_id', $rexer->id)
                       ->where('rexed_id', $id)
                       ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['message' => 'Unrexed successfully.']);
        } else {
            Rex::create([
                'rexer_id' => $rexer->id,
                'rexed_id' => $id,
            ]);

            // Create in-app notification
            Notification::create([
                'user_id' => $id,
                'title' => 'New Rex!',
                'body' => "{$rexer->name} just rex’d you.",
                'type' => 'user',
                'type_id' => $rexer->id,
            ]);

            // Send Firebase notification
            $target = User::find($id);
            if ($target && $target->device_token) {
                FirebaseHelper::sendNotification(
                    $target->device_token,
                    'New Rex!',
                    "{$rexer->name} just rex’d you.",
                    [
                        'type' => 'user',
                        'id' => $rexer->id
                    ]
                );
            }

            return response()->json(['message' => 'Rexed successfully.']);
        }
    }
}
