<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" 
     x-data 
     @scroll-to-edit-form.window="document.getElementById('edit-order-form')?.scrollIntoView({ behavior: 'smooth', block: 'start' })">

    {{-- Create Order Form --}}
    @if($showCreateForm)
    <div class="bg-green-100 p-4 rounded shadow mb-4 mt-4">
        <h2 class="text-lg font-semibold mb-2">Create New Order</h2>

        <div class="mb-2">
            <label class="block mb-1">Status</label>
            <input type="text" wire:model="status" class="w-full border rounded p-2">
        </div>

        <div class="mb-2">
            <label class="block mb-1">Total Amount</label>
            <input type="number" wire:model="total_amount" class="w-full border rounded p-2" step="0.01">
        </div>

        <div class="mb-2">
            <label class="block mb-1">User</label>
            <select wire:model="user_id" class="w-full border rounded p-2">
                <option value="">Select User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-2">
            <button wire:click="createOrder" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Create</button>
            <button wire:click="cancelCreateForm" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 transition">Cancel</button>
        </div>
    </div>
    @endif

    {{-- Edit Order Form --}}
    @if($orderId)
    <div id="edit-order-form" class="bg-yellow-100 p-4 rounded shadow mb-4 mt-4" x-init="$nextTick(() => { $dispatch('scroll-to-edit-form') })">
        <h2 class="text-lg font-semibold mb-2">Edit Order</h2>

        <div class="mb-2">
            <label class="block mb-1">Status</label>
            <input type="text" wire:model="status" class="w-full border rounded p-2">
        </div>

        <div class="mb-2">
            <label class="block mb-1">Total Amount</label>
            <input type="number" wire:model="total_amount" class="w-full border rounded p-2" step="0.01">
        </div>

        <div class="mb-2">
            <label class="block mb-1">User</label>
            <select wire:model="user_id" class="w-full border rounded p-2">
                <option value="">Select User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-2">
            <button wire:click="updateOrder" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Update</button>
            <button wire:click="$set('orderId', null)" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 transition">Cancel</button>
        </div>
    </div>
    @endif

    {{-- Search Filter --}}
    <div class="flex flex-wrap gap-4 items-center mt-6">
        <form wire:submit.prevent="applySearch" class="flex gap-2 flex-1">
            <div class="relative w-full">
                <input type="text" wire:model.defer="searchInput" placeholder="Search orde.." class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 pr-8">
                @if(strlen($searchInput ?? '') > 0)
                    <button type="button" wire:click="$set('searchInput', ''); $wire.applySearch()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                @endif
            </div>
            <button type="submit" class="inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Search</button>
            <button type="button" wire:click="resetFilters" class="inline-block px-3 py-1 bg-gray-400 text-white rounded hover:bg-gray-500">Reset</button>
        </form>

        <div class="ml-auto">
            <button wire:click="openCreateForm" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                Add Order
            </button>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="mt-6 overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @foreach(['ID','Status','Total Amount','User','Created At'] as $col)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $col }}</th>
                    @endforeach
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3">{{ $order->id }}</td>
                        <td class="px-6 py-3">{{ $order->status }}</td>
                        <td class="px-6 py-3">{{ number_format($order->total_amount, 2) }}</td>
                        <td class="px-6 py-3">{{ $order->user->name ?? '-' }}</td>
                        <td class="px-6 py-3">
                            {{ $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('Y-m-d') : '-' }}
                        </td>
                        <td class="px-6 py-3 text-right space-x-2">
                            <button wire:click="editOrder({{ $order->id }})" class="inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Edit</button>
                            <button x-data @click="if (confirm('Are you sure?')) { $wire.deleteOrder({{ $order->id }}) }" class="inline-block px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 mb-10">{{ $orders->links() }}</div>
</div>
