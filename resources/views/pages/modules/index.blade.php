<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Modules</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Enable or disable ERP modules</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <form method="POST" action="{{ route('modules.update') }}">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Finance</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Financial management features</p>
                            </div>
                            <input type="checkbox" name="finance" value="1" {{ $modules->finance ?? false ? 'checked' : '' }} class="rounded border-gray-300 text-violet-600 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Inventory</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Stock and inventory management</p>
                            </div>
                            <input type="checkbox" name="inventory" value="1" {{ $modules->inventory ?? false ? 'checked' : '' }} class="rounded border-gray-300 text-violet-600 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Reports</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Analytics and reporting tools</p>
                            </div>
                            <input type="checkbox" name="reports" value="1" {{ $modules->reports ?? false ? 'checked' : '' }} class="rounded border-gray-300 text-violet-600 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Messaging</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Communication and chat features</p>
                            </div>
                            <input type="checkbox" name="messaging" value="1" {{ $modules->messaging ?? false ? 'checked' : '' }} class="rounded border-gray-300 text-violet-600 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">GPS Tracking</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Location and route tracking</p>
                            </div>
                            <input type="checkbox" name="gps" value="1" {{ $modules->gps ?? false ? 'checked' : '' }} class="rounded border-gray-300 text-violet-600 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">POS</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Point of sale system</p>
                            </div>
                            <input type="checkbox" name="pos" value="1" {{ $modules->pos ?? false ? 'checked' : '' }} class="rounded border-gray-300 text-violet-600 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end">
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-violet-600 hover:bg-violet-700">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
