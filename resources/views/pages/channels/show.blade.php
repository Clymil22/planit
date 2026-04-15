<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">{{ $channel->name }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Channel details</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('channels.edit', $channel->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Edit</a>
                <a href="{{ route('channels') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Back</a>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Description</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ $channel->description ?? 'No description provided.' }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Messages ({{ $messages->count() }})</h3>
                        @if($messages->count() > 0)
                            <div class="space-y-3 max-h-96 overflow-y-auto">
                                @foreach($messages as $message)
                                    <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <div class="flex justify-between items-start mb-1">
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $message->sender_name ?? 'Unknown' }}</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $message->created_at ? $message->created_at->format('H:i') : '' }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $message->content }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">No messages in this channel.</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Channel Info</h3>
                        <dl class="space-y-3">
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($channel->type === 'general') bg-gray-100 text-gray-800 
                                    @elseif($channel->type === 'project') bg-blue-100 text-blue-800 
                                    @elseif($channel->type === 'support') bg-green-100 text-green-800 
                                    @else bg-purple-100 text-purple-800 
                                    @endif">
                                    {{ ucfirst($channel->type) }}
                                </span>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Created</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $channel->created_at ? $channel->created_at->format('M d, Y') : 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
