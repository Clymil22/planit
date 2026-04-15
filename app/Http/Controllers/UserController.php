<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $orgId = $request->user()->organisation_id;
        
        $users = DB::table('users')
            ->where('organisation_id', $orgId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('pages.users.index', compact('users'));
    }

    public function show(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $user = DB::table('users')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$user) {
            abort(404);
        }
        
        $profile = DB::table('profiles')->where('id', $user->id)->first();
        
        return view('pages.users.show', compact('user', 'profile'));
    }
}
