<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Orders Being Prepared</h2>
                <button wire:click="$refresh"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Refresh
                </button>
            </div>

            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('message') }}
                </div>
            @endif

            @if ($orders->count() > 0)
                <div class="space-y-4">
                    @foreach ($orders as $order)
                        <div class="border dark:border-gray-700 rounded-lg p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">
                                        Order #{{ $order->id }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Customer: {{ $order->customer->name }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Contact: {{ $order->customer->email }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Ordered: {{ $order->created_at->format('M d, Y h:i A') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-bold text-indigo-600">
                                        RM{{ number_format($order->total_price, 2) }}</p>
                                    <span class="px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                                        Preparing
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
                                <div class="mb-4">
                                    <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-1">Customer Notes:</h4>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $order->customer_notes }}</p>
                                </div>
                            @endif

                            @if ($order->vendor_notes)
                                <div class="mb-4">
                                    <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-1">Your Notes:</h4>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $order->vendor_notes }}</p>
                                </div>
                            @endif

                            <button wire:click="markAsDone({{ $order->id }})"
                                onclick="return confirm('Mark this order as ready for pickup?')"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Mark as Ready for Pickup
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 dark:text-gray-400">No orders being prepared at the moment.</p>
                </div>
            @endif
        </div>
    </div>
</div>
