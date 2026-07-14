@section('title', 'Account Settings')

<div>
    <h1 class="text-2xl font-bold mb-6" style="color: var(--td-text);">
        <i class="fa-solid fa-gear mr-2" style="color: var(--td-primary);"></i> Account Settings
    </h1>

    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 px-4 py-3 rounded-xl text-green-800 bg-green-100 border border-green-200">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Tabs -->
    <div class="flex gap-1 mb-6 border-b" style="border-color: var(--td-border);">
        <button wire:click="$set('activeTab', 'profile')"
                class="px-4 py-2.5 text-sm font-semibold border-b-2 transition-all -mb-px"
                :class="activeTab === 'profile' ? '' : ''"
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
    <form wire:submit="saveProfile" class="max-w-lg">
        <!-- Avatar -->
        <div class="td-card mb-5 flex items-center gap-5">
            @if($user->profile_photo_path)
                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                     class="w-20 h-20 rounded-full object-cover border-4" style="border-color: var(--td-primary);">
            @else
                <img src="{{ asset('images/placeholder-avatar.png') }}" alt="{{ $user->name }}"
                     class="w-20 h-20 rounded-full object-cover border-4 bg-gray-100" style="border-color: var(--td-primary);">
            @endif
            <div>
                <label for="photo" class="td-btn-primary cursor-pointer text-sm py-1.5 px-3">
                    <i class="fa-solid fa-camera"></i> Change Photo
                </label>
                <input wire:model="photo" type="file" id="photo" accept="image/*" class="hidden">
                @if($photo)
                    <p class="text-xs mt-1" style="color: var(--td-muted);">New photo selected — save to apply</p>
                @endif
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
    @endif

    <!-- Security Tab -->
    @if($activeTab === 'security')
    <form wire:submit="savePassword" class="max-w-lg">
        <div class="td-card mb-6">
            <h3 class="font-bold mb-4" style="color: var(--td-text);">Change Password</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color: var(--td-text);">Current Password</label>
                    <input wire:model="currentPassword" type="password"
                           class="w-full px-4 py-2.5 rounded-xl border outline-none text-sm"
                           style="border-color: var(--td-border); background: var(--td-bg); color: var(--td-text);">
                    @error('currentPassword')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color: var(--td-text);">New Password</label>
                    <input wire:model="newPassword" type="password"
                           class="w-full px-4 py-2.5 rounded-xl border outline-none text-sm"
                           style="border-color: var(--td-border); background: var(--td-bg); color: var(--td-text);">
                    @error('newPassword')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color: var(--td-text);">Confirm New Password</label>
                    <input wire:model="newPasswordConfirmation" type="password"
                           class="w-full px-4 py-2.5 rounded-xl border outline-none text-sm"
                           style="border-color: var(--td-border); background: var(--td-bg); color: var(--td-text);">
                </div>
            </div>
        </div>

        <button type="submit" class="td-btn-primary px-6 py-2.5">
            <i class="fa-solid fa-key"></i> Update Password
        </button>
    </form>
    @endif
</div>
