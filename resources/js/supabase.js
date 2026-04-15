import { createClient } from '@supabase/supabase-js'

const supabaseUrl = import.meta.env.VITE_SUPABASE_URL
const supabaseAnonKey = import.meta.env.VITE_SUPABASE_ANON_KEY

export const supabase = createClient(supabaseUrl, supabaseAnonKey)

// Helper function to get the current organisation ID from Laravel auth
// Since we're using Laravel auth, the org_id is available server-side
// For client-side queries, we'll fetch it from an API endpoint
export async function getAuthOrgId() {
    try {
        const response = await fetch('/api/user/org-id')
        if (!response.ok) return null
        const data = await response.json()
        return data.organisation_id
    } catch (error) {
        console.error('Error fetching organisation ID:', error)
        return null
    }
}

// Helper to check if user is authenticated
export async function isAuthenticated() {
    // Laravel handles authentication server-side
    // This checks if the session is valid
    try {
        const response = await fetch('/api/user')
        return response.ok
    } catch (error) {
        return false
    }
}

// Helper to get current user
export async function getCurrentUser() {
    try {
        const response = await fetch('/api/user')
        if (!response.ok) return null
        return await response.json()
    } catch (error) {
        console.error('Error fetching user:', error)
        return null
    }
}

// Real-time subscription helper
export function subscribeToTable(tableName, filter = {}, callback) {
    return supabase
        .channel(`table-${tableName}`)
        .on('postgres_changes', { event: '*', schema: 'public', table: tableName, filter }, callback)
        .subscribe()
}

export default supabase
