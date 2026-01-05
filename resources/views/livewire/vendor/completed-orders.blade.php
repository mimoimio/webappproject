<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Completed Orders</h2>
                <button wire:click="$refresh"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Refresh
                </button>
            </div>

            @if ($orders->count() > 0)
                <div class="space-y-4">
                    @foreach ($orders as $order)
                        <div class="border dark:border-gray-700 rounded-lg p-6 bg-gray-50 dark:bg-gray-900">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">
                                        Order #{{ $order->id }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Customer: {{ $order->customer->name }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Completed: {{ $order->updated_at->format('M d, Y h:i A') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-bold text-indigo-600">
                                        RM{{ number_format($order->total_price, 2) }}</p>
                                    <span class="px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                                        Completed
                                    </span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Items:</h4>
                                <ul class="space-y-1">
                                    @foreach ($order->items as $item)
                                        <li class="text-gray-600 dark:text-gray-400">
                                            {{ $item->menu->name }} x{{ $item->quantity }} -
                                            RM{{ number_format($item->price * $item->quantity, 2) }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            @if ($order->customer_notes)
                                <div class="mb-2">
                                    <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-1">Customer Notes:</h4>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $order->customer_notes }}</p>
                                </div>
                            @endif

                            @if ($order->vendor_notes)
                                <div>
                                    <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-1">Your Notes:</h4>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $order->vendor_notes }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 dark:text-gray-400">No completed orders yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
