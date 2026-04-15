<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">{{ $order->title }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Order details</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('orders.edit', $order->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Edit</a>
                <a href="{{ route('orders') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Back</a>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Items</h3>
                        @if($items->count() > 0)
                            <div class="space-y-3">
                                @foreach($items as $item)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $item->name }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Qty: {{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-gray-900 dark:text-white">${{ number_format($item->quantity * $item->unit_price, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">No items in this order.</p>
                        @endif
                    </div>
                </div>
                @if($order->notes)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Notes</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ $order->notes }}</p>
                    </div>
                </div>
                @endif
            </div>
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Summary</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Subtotal</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">${{ number_format($order->subtotal, 2) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Tax (10%)</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">${{ number_format($order->tax, 2) }}</dd>
                            </div>
                            <div class="flex justify-between border-t border-gray-200 dark:border-gray-700 pt-3">
                                <dt class="text-sm font-medium text-gray-900 dark:text-white">Total</dt>
                                <dd class="text-lg font-bold text-gray-900 dark:text-white">${{ number_format($order->total, 2) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($order->status === 'new') bg-blue-100 text-blue-800 
                                    @elseif($order->status === 'processing') bg-yellow-100 text-yellow-800 
                                    @elseif($order->status === 'completed') bg-green-100 text-green-800 
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800 
                                    @else bg-gray-100 text-gray-800 
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($order->priority === 'urgent') bg-red-100 text-red-800 
                                    @elseif($order->priority === 'high') bg-orange-100 text-orange-800 
                                    @elseif($order->priority === 'normal') bg-blue-100 text-blue-800 
                                    @else bg-gray-100 text-gray-800 
                                    @endif">
                                    {{ ucfirst($order->priority) }} Priority
                                </span>
                            </div>
                            @if($order->due_date)
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                Due: {{ $order->due_date->format('M d, Y') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
