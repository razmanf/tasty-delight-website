<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Search & Header --}}
    <div class="flex justify-between items-center py-4">
        <h2 class="text-2xl font-semibold text-gray-700">Users</h2>
        <div class="relative w-64">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search use.."
                class="border border-gray-300 rounded px-4 py-2 w-full pr-8"
            />
            @if(strlen($search ?? '') > 0)
                <button type="button" wire:click="$set('search', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            @endif
        </div>
    </div>

    {{-- Success Message --}}
    @if (session()->has('success'))
        <div class="mb-4 text-green-600">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @foreach(['ID','Name','Email','Role','Created At'] as $col)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $col }}
                        </th>
                    @endforeach
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $user->role }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->created_at->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button
                                wire:click="confirmDelete({{ $user->id }})"
                                class="inline-block px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 mb-10">
        {{ $users->links() }}
    </div>

    {{-- Delete Confirmation Modal --}}
    @if ($userToDelete)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow-lg">
                <h3 class="text-lg font-semibold mb-4">Confirm Delete</h3>
                <p>Are you sure you want to delete this user?</p>
                <div class="mt-6 flex justify-end space-x-2">
                    <button
                        wire:click="$set('userToDelete', null)"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition"
                    >
                        Cancel
                    </button>
                    <button
                        wire:click="deleteUser"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>