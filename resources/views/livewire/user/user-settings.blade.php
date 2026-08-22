@section('title', 'Account Settings')

<div>
    <h1 class="text-2xl font-bold mb-6" style="color: var(--td-text);">
        <i class="fa-solid fa-gear mr-2" style="color: var(--td-primary);"></i> Account Settings
    </h1>

    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 px-4 py-3 rounded-xl text-green-800 bg-green-100 border border-green-200 dark:bg-green-900/20 dark:border-green-800 dark:text-green-400">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 px-4 py-3 rounded-xl text-red-800 bg-red-100 border border-red-200 dark:bg-red-900/20 dark:border-red-800 dark:text-red-400">
            <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Tabs -->
    <div class="flex gap-1 mb-6 border-b" style="border-color: var(--td-border);">
        <button wire:click="$set('activeTab', 'profile')"
                class="px-4 py-2.5 text-sm font-semibold border-b-2 transition-all -mb-px"
                style="{{ $activeTab === 'profile' ? 'border-color: var(--td-primary); color: var(--td-primary);' : 'border-color: transparent; color: var(--td-muted);' }}">
            <i class="fa-solid fa-user mr-1.5"></i> Profile
        </button>
        <button wire:click="$set('activeTab', 'security')"
                class="px-4 py-2.5 text-sm font-semibold border-b-2 transition-all -mb-px"
                style="{{ $activeTab === 'security' ? 'border-color: var(--td-primary); color: var(--td-primary);' : 'border-color: transparent; color: var(--td-muted);' }}">
            <i class="fa-solid fa-shield-halved mr-1.5"></i> Security
        </button>
    </div>

    <!-- Profile Tab -->
    @if($activeTab === 'profile')
    <div x-data="{ showPhotoDeleteModal: false }">
        <form wire:submit="saveProfile" class="max-w-lg">
            <!-- Avatar -->
            <div class="td-card mb-5 flex items-center gap-5">
                @if($photo)
                    <img src="{{ $photo->temporaryUrl() }}" alt="Preview" draggable="false"
                         class="w-20 h-20 rounded-full object-cover border-4 select-none pointer-events-none" style="border-color: var(--td-primary);">
                @elseif($user->profile_photo_path)
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" draggable="false"
                         class="w-20 h-20 rounded-full object-cover border-4 select-none pointer-events-none" style="border-color: var(--td-primary);">
                @else
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" draggable="false"
                         class="w-20 h-20 rounded-full object-cover border-4 bg-gray-100 select-none pointer-events-none" style="border-color: var(--td-primary);">
                @endif
                <div>
                    <div class="flex items-center gap-2">
                        <label for="photo" class="td-btn-primary cursor-pointer text-sm py-1.5 px-3">
                            <i class="fa-solid fa-camera"></i> Change Photo
                        </label>
                        @if($user->profile_photo_path)
                            <button type="button" @click="showPhotoDeleteModal = true" class="px-3 py-1.5 rounded-lg text-sm font-semibold bg-red-100 text-red-600 hover:bg-red-200 transition-colors">
                                <i class="fa-solid fa-trash-can"></i> Remove
                            </button>
                        @endif
                    </div>
                    <input wire:model="photo" type="file" id="photo" accept="image/*" class="hidden">

                    @error('photo')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Name -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1.5" style="color: var(--td-text);">Full Name</label>
                <input wire:model="name" type="text"
                       class="w-full px-4 py-2.5 rounded-xl border outline-none transition-all text-sm"
                       style="border-color: var(--td-border); background: var(--td-bg); color: var(--td-text);">
                @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Email (read-only) -->
            <div class="mb-6">
                <label class="block text-sm font-semibold mb-1.5" style="color: var(--td-text);">
                    Email Address
                    <span class="ml-2 inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full"
                          style="background: #3B82F61A; color: #3B82F6;">
                        <i class="fa-solid fa-lock text-xs"></i> Cannot be changed
                    </span>
                </label>
                <input type="email" value="{{ $user->email }}" disabled
                       class="w-full px-4 py-2.5 rounded-xl border text-sm opacity-60 cursor-not-allowed"
                       style="border-color: var(--td-border); background: var(--td-bg); color: var(--td-text);">
                <p class="text-xs mt-1" style="color: var(--td-muted);">For security, email addresses cannot be changed after account creation.</p>
            </div>

            <button type="submit" class="td-btn-primary px-6 py-2.5">
                <i class="fa-solid fa-floppy-disk"></i> Save Profile
            </button>
        </form>

        <!-- Custom Alpine Modal for Photo Deletion -->
        <div x-show="showPhotoDeleteModal"
             style="display: none;"
             class="fixed inset-0 z-[100] flex items-center justify-center px-4"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
             
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

            <div class="td-card relative z-10 max-w-sm w-full p-6 text-center transform shadow-2xl"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                 
                <div class="w-16 h-16 rounded-full mx-auto flex items-center justify-center mb-4 shadow-inner bg-red-100 text-red-500">
                    <i class="fa-solid fa-image text-2xl"></i>
                </div>
                
                <h3 class="text-xl font-bold mb-2" style="color: var(--td-text);">Remove Photo?</h3>
                <p class="text-sm mb-6" style="color: var(--td-muted);">
                    Are you sure you want to remove your custom profile photo and revert to the default avatar?
                </p>
                
                <div class="flex gap-3 w-full">
                    <button type="button" @click="showPhotoDeleteModal = false" class="flex-1 py-2.5 rounded-xl font-bold bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors" style="color: var(--td-text);">
                        Cancel
                    </button>
                    <button type="button" wire:click="deleteProfilePhoto" @click="showPhotoDeleteModal = false" class="flex-1 py-2.5 rounded-xl font-bold text-white shadow-md transition-transform hover:scale-105 bg-red-500 hover:bg-red-600">
                        Remove
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Security Tab -->
    @if($activeTab === 'security')
    <form wire:submit="savePassword" class="max-w-lg" x-data="{ showPasswords: false }">
        <!-- Hidden Username Field for Password Managers -->
        <input type="text" autocomplete="username" value="{{ auth()->user()->email }}" style="display: none;">
        
        <div class="td-card mb-6">
            <h3 class="font-bold mb-4" style="color: var(--td-text);">Change Password</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color: var(--td-text);">Current Password</label>
                    <input wire:model="currentPassword" :type="showPasswords ? 'text' : 'password'" autocomplete="current-password"
                           class="w-full px-4 py-2.5 rounded-xl border outline-none text-sm"
                           style="border-color: var(--td-border); background: var(--td-bg); color: var(--td-text);">
                    @error('currentPassword')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color: var(--td-text);">New Password</label>
                    <input wire:model="newPassword" :type="showPasswords ? 'text' : 'password'" autocomplete="new-password"
                           class="w-full px-4 py-2.5 rounded-xl border outline-none text-sm"
                           style="border-color: var(--td-border); background: var(--td-bg); color: var(--td-text);">
                    @error('newPassword')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color: var(--td-text);">Confirm New Password</label>
                    <input wire:model="newPasswordConfirmation" :type="showPasswords ? 'text' : 'password'" autocomplete="new-password"
                           class="w-full px-4 py-2.5 rounded-xl border outline-none text-sm"
                           style="border-color: var(--td-border); background: var(--td-bg); color: var(--td-text);">
                </div>

                <!-- Show Password Toggle -->
                <div class="flex items-center gap-2 mt-2">
                    <input type="checkbox" id="show_password_settings" x-model="showPasswords" class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500" style="border-color: var(--td-border);">
                    <label for="show_password_settings" class="text-sm cursor-pointer" style="color: var(--td-muted);">Show Password</label>
                </div>
            </div>
        </div>

        <button type="submit" class="td-btn-primary px-6 py-2.5">
            <i class="fa-solid fa-key"></i> Update Password
        </button>
    </form>

    <div class="max-w-lg mt-8" x-data="{ showDeleteModal: false }">
        <div class="p-5 border border-red-200 bg-red-50 dark:bg-red-900/10 dark:border-red-900/50 rounded-xl">
            <h3 class="font-bold text-red-700 dark:text-red-400 mb-2">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i> Danger Zone
            </h3>
            <p class="text-sm text-red-600/80 dark:text-red-400/80 mb-4">
                Permanently delete your account and all associated data. This action cannot be undone.
            </p>
            <button type="button" @click="showDeleteModal = true" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                Delete Account
            </button>
        </div>

        <!-- Delete Confirmation Modal (Static Backdrop) -->
        <div x-show="showDeleteModal" style="display: none;"
             class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <!-- Removed @click.outside to create a static backdrop -->
            <form wire:submit="deleteAccount" class="relative w-full max-w-md bg-white dark:bg-gray-800 rounded-xl shadow-2xl p-6 border border-gray-100 dark:border-gray-700"
                  x-data="{ showDeletePassword: false }"
                  x-transition:enter="transition ease-out duration-300 delay-100"
                  x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                  x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                  x-transition:leave="transition ease-in duration-200"
                  x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                  x-transition:leave-end="opacity-0 translate-y-8 scale-95">
                
                <!-- Hidden Username Field for Password Managers -->
                <input type="text" autocomplete="username" value="{{ auth()->user()->email }}" style="display: none;">
                
                <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mb-4 text-red-600 dark:text-red-500">
                    <i class="fa-solid fa-trash-can text-xl"></i>
                </div>
                
                <h3 class="text-xl font-bold mb-2" style="color: var(--td-text);">Delete Account?</h3>
                <p class="text-sm mb-4" style="color: var(--td-muted);">
                    Are you absolutely sure? This action is permanent and cannot be undone.
                </p>
                
                <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded-lg mb-6 border" style="border-color: var(--td-border);">
                    <p class="text-xs font-bold uppercase mb-2" style="color: var(--td-text);">The following will be permanently wiped:</p>
                    <ul class="text-sm space-y-1" style="color: var(--td-muted);">
                        <li><i class="fa-solid fa-xmark text-red-500 mr-2"></i> Profile Information</li>
                        <li><i class="fa-solid fa-xmark text-red-500 mr-2"></i> Order History</li>
                        <li><i class="fa-solid fa-xmark text-red-500 mr-2"></i> Saved Favorites</li>
                        <li><i class="fa-solid fa-xmark text-red-500 mr-2"></i> Active Carts</li>
                        <li><i class="fa-solid fa-xmark text-red-500 mr-2"></i> Reviews</li>
                    </ul>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-semibold mb-1.5" style="color: var(--td-text);">Confirm with Password</label>
                    <input wire:model="passwordForDeletion" :type="showDeletePassword ? 'text' : 'password'" placeholder="Enter your current password" autocomplete="current-password"
                           class="w-full px-4 py-2.5 rounded-xl border outline-none text-sm"
                           style="border-color: var(--td-border); background: var(--td-bg); color: var(--td-text);">
                    
                    <div class="flex items-center gap-2 mt-3">
                        <input type="checkbox" id="show_password_delete" x-model="showDeletePassword" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500" style="border-color: var(--td-border);">
                        <label for="show_password_delete" class="text-sm cursor-pointer" style="color: var(--td-muted);">Show Password</label>
                    </div>

                    @error('passwordForDeletion')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div class="flex gap-3 justify-end">
                    <button type="button" @click="showDeleteModal = false" class="px-5 py-2.5 rounded-lg text-sm font-bold border hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" style="border-color: var(--td-border); color: var(--td-text);">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-lg text-sm font-bold bg-red-600 hover:bg-red-700 text-white transition-colors shadow-sm">
                        Yes, I am sure
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
