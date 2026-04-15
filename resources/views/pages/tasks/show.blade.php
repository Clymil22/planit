<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">{{ $task->title }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Task details</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('tasks.edit', $task->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Edit</a>
                <a href="{{ route('tasks') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Back</a>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Description</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ $task->description ?? 'No description provided.' }}</p>
                    </div>
                </div>
            </div>
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Task Status</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($task->status === 'todo') bg-gray-100 text-gray-800 
                                    @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800 
                                    @elseif($task->status === 'review') bg-yellow-100 text-yellow-800 
                                    @elseif($task->status === 'done') bg-green-100 text-green-800 
                                    @elseif($task->status === 'cancelled') bg-red-100 text-red-800 
                                    @else bg-gray-100 text-gray-800 
                                    @endif">
                                    {{ ucfirst($task->status) }}
                                </span>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($task->priority === 'urgent') bg-red-100 text-red-800 
                                    @elseif($task->priority === 'high') bg-orange-100 text-orange-800 
                                    @elseif($task->priority === 'normal') bg-blue-100 text-blue-800 
                                    @else bg-gray-100 text-gray-800 
                                    @endif">
                                    {{ ucfirst($task->priority) }} Priority
                                </span>
                            </div>
                            @if($task->due_date)
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                Due: {{ $task->due_date->format('M d, Y') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Details</h3>
                        <dl class="space-y-3">
                            @if($task->project_title)
                            <div>
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Project</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $task->project_title }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Created</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $task->created_at ? $task->created_at->format('M d, Y') : 'N/A' }}</dd>
                            </div>
                            @if($task->completed_at)
                            <div>
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Completed</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $task->completed_at->format('M d, Y') }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
