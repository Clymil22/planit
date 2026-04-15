<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Store a newly created message.
     */
    public function store(Request $request, string $channelId): JsonResponse
    {
        $request->validate([
            'content' => 'required|string',
            'message_type' => 'in:text,image,file,system',
        ]);

        $orgId = $request->user()->organisation_id;
        $userId = $request->user()->id;
        
        // Verify channel belongs to user's organisation
        $channel = DB::table('channels')
            ->where('id', $channelId)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$channel) {
            return response()->json(['message' => 'Channel not found'], 404);
        }
        
        $messageId = DB::table('messages')->insertGetId([
            'channel_id' => $channelId,
            'sender_id' => $userId,
            'content' => $request->content,
            'message_type' => $request->message_type ?? 'text',
            'created_at' => now(),
        ], 'id');

        $message = DB::table('messages')
            ->join('profiles', 'messages.sender_id', '=', 'profiles.id')
            ->where('messages.id', $messageId)
            ->select('messages.*', 'profiles.full_name as sender_name')
            ->first();
        
        return response()->json($message, 201);
    }
}
