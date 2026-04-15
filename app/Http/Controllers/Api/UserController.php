<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Get the current user's organisation ID
     */
    public function getOrganisationId(Request $request): JsonResponse
    {
        $user = $request->user();
        
        return response()->json([
            'organisation_id' => $user->organisation_id,
        ]);
    }
}
