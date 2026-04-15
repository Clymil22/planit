<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">{{ $client->full_name }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Client details</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('clients.edit', $client->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Edit</a>
                <a href="{{ route('clients') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Back</a>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Contact Information</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Full Name</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $client->full_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Company</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $client->company_name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Email</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $client->email ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Phone</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $client->phone ?? 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Additional Information</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Address</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $client->address ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Notes</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $client->notes ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Created</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $client->created_at ? $client->created_at->format('M d, Y') : 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
