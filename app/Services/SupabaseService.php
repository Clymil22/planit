<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SupabaseService
{
    /**
     * Get the current user's organisation ID
     * 
     * @return string|null
     */
    public function getAuthOrgId()
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }
        
        return $user->organisation_id;
    }

    /**
     * Check if a user belongs to an organisation
     * 
     * @param string $orgId
     * @return bool
     */
    public function userBelongsToOrganisation(string $orgId): bool
    {
        $userOrgId = $this->getAuthOrgId();
        return $userOrgId === $orgId;
    }

    /**
     * Get organisation details
     * 
     * @param string|null $orgId
     * @return object|null
     */
    public function getOrganisation(?string $orgId = null)
    {
        $orgId = $orgId ?? $this->getAuthOrgId();
        
        if (!$orgId) {
            return null;
        }

        return DB::table('organisations')
            ->where('id', $orgId)
            ->first();
    }

    /**
     * Get organisation modules/settings
     * 
     * @param string|null $orgId
     * @return object|null
     */
    public function getOrganisationModules(?string $orgId = null)
    {
        $orgId = $orgId ?? $this->getAuthOrgId();
        
        if (!$orgId) {
            return null;
        }

        return DB::table('organisation_modules')
            ->where('organisation_id', $orgId)
            ->first();
    }

    /**
     * Check if a module is enabled for the organisation
     * 
     * @param string $module
     * @param string|null $orgId
     * @return bool
     */
    public function isModuleEnabled(string $module, ?string $orgId = null): bool
    {
        $modules = $this->getOrganisationModules($orgId);
        
        if (!$modules) {
            return false;
        }

        return (bool) ($modules->$module ?? false);
    }

    /**
     * Get user profile
     * 
     * @param int|null $userId
     * @return object|null
     */
    public function getUserProfile(?int $userId = null)
    {
        $userId = $userId ?? auth()->id();
        
        if (!$userId) {
            return null;
        }

        return DB::table('profiles')
            ->where('id', $userId)
            ->first();
    }

    /**
     * Get stores accessible to the current user
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getUserStores()
    {
        $orgId = $this->getAuthOrgId();
        
        if (!$orgId) {
            return collect();
        }

        // Get stores where user is assigned or all stores in organisation
        $assignedStores = DB::table('user_stores')
            ->where('user_id', auth()->id())
            ->pluck('store_id');

        if ($assignedStores->isEmpty()) {
            // Return all stores in organisation if no specific assignment
            return DB::table('stores')
                ->where('organisation_id', $orgId)
                ->where('is_active', true)
                ->get();
        }

        return DB::table('stores')
            ->where('organisation_id', $orgId)
            ->whereIn('id', $assignedStores)
            ->where('is_active', true)
            ->get();
    }

    /**
     * Get user's primary store
     * 
     * @return object|null
     */
    public function getPrimaryStore()
    {
        return DB::table('user_stores')
            ->where('user_id', auth()->id())
            ->where('is_primary', true)
            ->first();
    }
}
