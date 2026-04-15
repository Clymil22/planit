<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChannelController extends Controller
{
    public function index(Request $request)
    {
        $orgId = $request->user()->organisation_id;
        
        $channels = DB::table('channels')
            ->where('organisation_id', $orgId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('pages.channels.index', compact('channels'));
    }

    public function create()
    {
        return view('pages.channels.create');
    }

    public function store(Request $request)
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

        DB::table('channel_members')->insert([
            'channel_id' => $channelId,
            'user_id' => $userId,
            'joined_at' => now(),
        ]);
        
        return redirect()->route('channels')->with('success', 'Channel created successfully.');
    }

    public function show(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $channel = DB::table('channels')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$channel) {
            abort(404);
        }
        
        $messages = DB::table('messages')
            ->join('profiles', 'messages.sender_id', '=', 'profiles.id')
            ->where('messages.channel_id', $id)
            ->select('messages.*', 'profiles.full_name as sender_name')
            ->orderBy('messages.created_at', 'asc')
            ->get();
        
        return view('pages.channels.show', compact('channel', 'messages'));
    }

    public function edit(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $channel = DB::table('channels')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$channel) {
            abort(404);
        }
        
        return view('pages.channels.edit', compact('channel'));
    }

    public function update(Request $request, $id)
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
            abort(404);
        }

        DB::table('channels')
            ->where('id', $id)
            ->update($request->only(['name', 'description', 'type']));
        
        return redirect()->route('channels')->with('success', 'Channel updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $channel = DB::table('channels')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$channel) {
            abort(404);
        }

        DB::table('channels')->where('id', $id)->delete();
        DB::table('messages')->where('channel_id', $id)->delete();
        DB::table('channel_members')->where('channel_id', $id)->delete();
        
        return redirect()->route('channels')->with('success', 'Channel deleted successfully.');
    }
}
