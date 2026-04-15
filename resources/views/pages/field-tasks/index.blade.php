<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Field Tasks</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Manage field and delivery tasks</p>
            </div>
            <a href="{{ route('field-tasks.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-violet-600 hover:bg-violet-700">Create Field Task</a>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Scheduled</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @if($fieldTasks->count() > 0)
                            @foreach($fieldTasks as $fieldTask)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $fieldTask->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($fieldTask->type === 'delivery') bg-blue-100 text-blue-800 
                                            @elseif($fieldTask->type === 'pickup') bg-green-100 text-green-800 
                                            @else bg-purple-100 text-purple-800 
                                            @endif">
                                            {{ ucfirst($fieldTask->type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($fieldTask->status === 'pending') bg-gray-100 text-gray-800 
                                            @elseif($fieldTask->status === 'in_progress') bg-blue-100 text-blue-800 
                                            @elseif($fieldTask->status === 'completed') bg-green-100 text-green-800 
                                            @elseif($fieldTask->status === 'cancelled') bg-red-100 text-red-800 
                                            @else bg-gray-100 text-gray-800 
                                            @endif">
                                            {{ ucfirst($fieldTask->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $fieldTask->scheduled_at ? $fieldTask->scheduled_at->format('M d, Y') : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('field-tasks.show', $fieldTask->id) }}" class="text-violet-600 hover:text-violet-900 dark:text-violet-400 dark:hover:text-violet-300">View</a>
                                        <a href="{{ route('field-tasks.edit', $fieldTask->id) }}" class="text-violet-600 hover:text-violet-900 dark:text-violet-400 dark:hover:text-violet-300">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    No field tasks found. Create your first field task to get started.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
