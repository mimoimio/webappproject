<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Available Vendors</h2>
                <button wire:click="$refresh"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Refresh
                </button>
            </div>

            @if ($vendors->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($vendors as $vendor)
                        <div class="border dark:border-gray-700 rounded-lg p-6 hover:shadow-lg transition">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-2">
                                {{ $vendor->business_name }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">
                                {{ $vendor->description ?? 'No description available' }}
                            </p>
                            <a href="{{ route('customer.vendor.menu', $vendor->id) }}"
                                class="inline-block bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                View Menu
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 dark:text-gray-400">No vendors available at the moment.</p>
                </div>
            @endif
        </div>
    </div>
</div>
