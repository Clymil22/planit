<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ChannelController extends Controller
{
    /**
     * Display a listing of channels.
     */
    public function index(Request $request): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $channels = DB::table('channels')
            ->where('organisation_id', $orgId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json($channels);
    }

    /**
     * Store a newly created channel.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'in:general,project,support,private',
        ]);

        $orgId = $request->user()->organisation_id;
        $userId = $request->user()->id;
        
        $channelId = DB::table('channels')->insertGetId([
            'organisation_id' => $orgId,
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type ?? 'general',
            'created_at' => now(),
        ], 'id');

        // Add current user as a member
        DB::table('channel_members')->insert([
            'channel_id' => $channelId,
            'user_id' => $userId,
            'joined_at' => now(),
        ]);

        $channel = DB::table('channels')->where('id', $channelId)->first();
        
        return response()->json($channel, 201);
    }

    /**
     * Display the specified channel.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $channel = DB::table('channels')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$channel) {
            return response()->json(['message' => 'Channel not found'], 404);
        }
        
        return response()->json($channel);
    }

    /**
     * Get channel messages.
     */
    public function messages(Request $request, string $id): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $channel = DB::table('channels')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$channel) {
            return response()->json(['message' => 'Channel not found'], 404);
        }
        
        $messages = DB::table('messages')
            ->join('profiles', 'messages.sender_id', '=', 'profiles.id')
            ->where('messages.channel_id', $id)
            ->select('messages.*', 'profiles.full_name as sender_name')
            ->orderBy('messages.created_at', 'asc')
            ->get();
        
        return response()->json($messages);
    }

    /**
     * Update the specified channel.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'in:general,project,support,private',
        ]);

        $orgId = $request->user()->organisation_id;
        
        $channel = DB::table('channels')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$channel) {
            return response()->json(['message' => 'Channel not found'], 404);
        }

        DB::table('channels')
            ->where('id', $id)
            ->update($request->only(['name', 'description', 'type']));

        $updatedChannel = DB::table('channels')->where('id', $id)->first();
        
        return response()->json($updatedChannel);
    }

    /**
     * Remove the specified channel.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $channel = DB::table('channels')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$channel) {
            return response()->json(['message' => 'Channel not found'], 404);
        }

        DB::table('channels')->where('id', $id)->delete();
        DB::table('messages')->where('channel_id', $id)->delete();
        DB::table('channel_members')->where('channel_id', $id)->delete();
        
        return response()->json(['message' => 'Channel deleted successfully']);
    }
}
