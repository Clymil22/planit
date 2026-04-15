<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">{{ $project->title }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Project details</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('projects.edit', $project->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Edit</a>
                <a href="{{ route('projects') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Back</a>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Description</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ $project->description ?? 'No description provided.' }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tasks ({{ $tasks->count() }})</h3>
                        @if($tasks->count() > 0)
                            <div class="space-y-3">
                                @foreach($tasks as $task)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $task->title }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ ucfirst($task->status) }}</p>
                                        </div>
                                        <a href="{{ route('tasks.show', $task->id) }}" class="text-violet-600 hover:text-violet-900 dark:text-violet-400 dark:hover:text-violet-300 text-sm">View</a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">No tasks in this project.</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Project Status</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($project->status === 'todo') bg-gray-100 text-gray-800 
                                    @elseif($project->status === 'in_progress') bg-blue-100 text-blue-800 
                                    @elseif($project->status === 'completed') bg-green-100 text-green-800 
                                    @elseif($project->status === 'cancelled') bg-red-100 text-red-800 
                                    @else bg-gray-100 text-gray-800 
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Progress</p>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                    <div class="bg-violet-600 h-2.5 rounded-full" style="width: {{ $project->progress }}%"></div>
                                </div>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $project->progress }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Details</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Created</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $project->created_at ? $project->created_at->format('M d, Y') : 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
