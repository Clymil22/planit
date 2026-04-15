<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Create Order</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Create a new order</p>
            </div>
            <a href="{{ route('orders') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Cancel</a>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <form method="POST" action="{{ route('orders.store') }}">
                @csrf
                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Client</label>
                            <select name="client_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Select a client (optional)</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Store</label>
                            <select name="store_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Select a store (optional)</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Assigned To</label>
                            <select name="assigned_to" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Select a user (optional)</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name ?? $user->email }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="new">New</option>
                                <option value="processing">Processing</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Priority</label>
                            <select name="priority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="low">Low</option>
                                <option value="normal" selected>Normal</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Due Date</label>
                            <input type="date" name="due_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                        <textarea name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Order Items</label>
                        <div id="order-items" class="space-y-3">
                            <div class="order-item grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                                <div class="md:col-span-2">
                                    <input type="text" name="items[0][name]" placeholder="Item name" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>
                                <div>
                                    <input type="number" name="items[0][quantity]" placeholder="Qty" required min="1" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>
                                <div>
                                    <input type="number" step="0.01" name="items[0][unit_price]" placeholder="Price" required min="0" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="addOrderItem()" class="mt-2 text-sm text-violet-600 hover:text-violet-900 dark:text-violet-400">+ Add Item</button>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end space-x-3">
                    <a href="{{ route('orders') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Cancel</a>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-violet-600 hover:bg-violet-700">Create Order</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        let itemIndex = 1;
        function addOrderItem() {
            const container = document.getElementById('order-items');
            const newItem = document.createElement('div');
            newItem.className = 'order-item grid grid-cols-1 md:grid-cols-4 gap-3 items-end';
            newItem.innerHTML = `
                <div class="md:col-span-2">
                    <input type="text" name="items[${itemIndex}][name]" placeholder="Item name" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <input type="number" name="items[${itemIndex}][quantity]" placeholder="Qty" required min="1" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="flex gap-2">
                    <input type="number" step="0.01" name="items[${itemIndex}][unit_price]" placeholder="Price" required min="0" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-900">✕</button>
                </div>
            `;
            container.appendChild(newItem);
            itemIndex++;
        }
    </script>
</x-app-layout>
