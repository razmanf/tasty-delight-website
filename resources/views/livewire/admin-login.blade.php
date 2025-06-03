<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white p-8 rounded shadow">
        <h2 class="text-2xl font-bold mb-6 text-center">Admin Login</h2>

        <form wire:submit.prevent="login" novalidate>
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email Address</label>
                <input
                    type="email"
                    id="email"
                    wire:model.lazy="email"
                    class="w-full px-3 py-2 border rounded @error('email') border-red-500 @enderror"
                    required
                    autofocus
                />
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700">Password</label>
                <input
                    type="password"
                    id="password"
                    wire:model.lazy="password"
                    class="w-full px-3 py-2 border rounded @error('password') border-red-500 @enderror"
                    required
                />
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition"
            >
                Login
            </button>
        </form>
    </div>
</div>
