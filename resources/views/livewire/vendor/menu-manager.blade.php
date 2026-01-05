<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Manage Menu</h2>

            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Add/Edit Form -->
            <div class="mb-8 border dark:border-gray-700 rounded-lg p-6 bg-gray-50 dark:bg-gray-900">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                    {{ $editingId ? 'Edit Menu Item' : 'Add New Menu Item' }}
                </h3>

                <form wire:submit.prevent="save" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                Name *
                            </label>
                            <input type="text" wire:model="name" class="w-full border rounded px-3 py-2"
                                placeholder="Menu item name">
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                Price *
                            </label>
                            <input type="number" wire:model="price" step="0.01" min="0"
                                class="w-full border rounded px-3 py-2" placeholder="0.00">
                            @error('price')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            Description
                        </label>
                        <textarea wire:model="description" rows="3" class="w-full border rounded px-3 py-2"
                            placeholder="Describe this menu item..."></textarea>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="isAvailable" class="mr-2">
                            <span class="text-gray-700 dark:text-gray-300">Available</span>
                        </label>
                    </div>

                    <div class="flex space-x-2">
                        <button type="submit"
                            class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            {{ $editingId ? 'Update' : 'Add' }} Menu Item
                        </button>
                        @if ($editingId)
                            <button type="button" wire:click="cancelEdit"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </button>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Menu List -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Current Menu Items</h3>
                    <button wire:click="$refresh"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Refresh
                    </button>
                </div>

                @if ($menus->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">Name</th>
                                    <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">Description</th>
                                    <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">Price</th>
                                    <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">Status</th>
                                    <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($menus as $menu)
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="px-4 py-3 text-gray-800 dark:text-gray-200">{{ $menu->name }}</td>
                                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400 text-sm">
                                            {{ Str::limit($menu->description, 50) }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-800 dark:text-gray-200">
                                            RM{{ number_format($menu->price, 2) }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <button wire:click="toggleAvailability({{ $menu->id }})"
                                                class="px-2 py-1 rounded text-sm {{ $menu->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $menu->is_available ? 'Available' : 'Unavailable' }}
                                            </button>
                                        </td>
                                        <td class="px-4 py-3">
                                            <button wire:click="edit({{ $menu->id }})"
                                                class="text-blue-600 hover:text-blue-800 mr-3">
                                                Edit
                                            </button>
                                            <button wire:click="delete({{ $menu->id }})"
                                                onclick="return confirm('Are you sure?')"
                                                class="text-red-600 hover:text-red-800">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">
                        No menu items yet. Add your first item above!
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
