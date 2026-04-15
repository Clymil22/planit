<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">{{ $product->name }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Product details</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('products.edit', $product->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Edit</a>
                <a href="{{ route('products') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Back</a>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Product Information</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Name</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $product->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600 dark:text-gray-400">SKU</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $product->sku ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Description</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $product->description ?? 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Pricing & Inventory</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Price</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">${{ number_format($product->price, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Stock</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $product->stock }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Created</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $product->created_at ? $product->created_at->format('M d, Y') : 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
