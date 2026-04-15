<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">User Profile</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $user->name ?? 'User Details' }}</p>
            </div>
            <a href="{{ route('users') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                Back to Users
            </a>
        </div>

        <!-- User details card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="h-20 w-20 rounded-full bg-violet-500 flex items-center justify-center text-white text-2xl font-bold mr-6">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $user->name ?? 'N/A' }}</h2>
                        <p class="text-gray-600 dark:text-gray-400">{{ $user->email ?? 'N/A' }}</p>
                        @if($user->role)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-violet-100 text-violet-800 mt-2">
                                {{ $user->role }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Account Information</h3>
                        <dl class="space-y-2">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-600 dark:text-gray-400">User ID</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->id }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Email Verified</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $user->email_verified_at ? 'Yes' : 'No' }}
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Created At</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $user->created_at ? $user->created_at->format('M d, Y H:i') : 'N/A' }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    @if($profile)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Profile Information</h3>
                        <dl class="space-y-2">
                            @if($profile->phone)
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Phone</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $profile->phone }}</dd>
                            </div>
                            @endif
                            @if($profile->address)
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Address</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $profile->address }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
