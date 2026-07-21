@section('title', 'Notifications')

<div x-data="{
    showConfirmModal: false,
    itemToDelete: null,
    confirmDelete(id) {
        this.itemToDelete = id;
        this.showConfirmModal = true;
    },
    executeDelete() {
        if(this.itemToDelete) {
            $wire.deleteNotification(this.itemToDelete);
            this.showConfirmModal = false;
            this.itemToDelete = null;
        }
    }
}">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold" style="color: var(--td-text);">
            <i class="fa-regular fa-bell mr-2" style="color: var(--td-primary);"></i> Notifications
        </h1>
        @if(auth()->user()->unreadNotifications->count() > 0)
        <button wire:click="markAllRead"
                class="text-sm font-medium hover:underline transition-colors"
                style="color: var(--td-primary);">
            <i class="fa-solid fa-check-double mr-1"></i> Mark all as read
        </button>
        @endif
    </div>

    @if($notifications->isEmpty())
        <div class="td-card text-center py-16">
            <i class="fa-regular fa-bell-slash text-5xl mb-4" style="color: var(--td-muted);"></i>
            <p class="text-lg font-semibold" style="color: var(--td-text);">No notifications</p>
            <p class="text-sm mt-1" style="color: var(--td-muted);">You're all caught up! 🎉</p>
        </div>
    @else
        <div class="space-y-2">
            @foreach($notifications as $notification)
            @php $data = $notification->data; @endphp
            <div class="td-card py-4 flex items-start gap-4 transition-all {{ is_null($notification->read_at) ? 'border-l-4' : '' }}"
                 style="{{ is_null($notification->read_at) ? 'border-left-color: var(--td-primary);' : '' }}">
                <!-- Icon or Thumbnail -->
                @if(isset($data['image']) && $data['image'])
                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 text-lg overflow-hidden border border-gray-100 shadow-sm">
                        <img src="{{ \Illuminate\Support\Str::startsWith($data['image'], ['http://', 'https://']) ? $data['image'] : asset('storage/' . $data['image']) }}" alt="Thumbnail" class="w-full h-full object-cover">
                    </div>
                @else
                    @php
                        $icon = $data['icon'] ?? 'heroicon-o-bell';
                        $color = $data['color'] ?? 'primary';
                        
                        $faIcon = 'fa-bell';
                        if (str_contains($icon, 'shopping-cart')) $faIcon = 'fa-cart-shopping';
                        if (str_contains($icon, 'check')) $faIcon = 'fa-check';
                        if (str_contains($icon, 'truck')) $faIcon = 'fa-truck';
                        if (str_contains($icon, 'gift') || str_contains($icon, 'sparkles')) $faIcon = 'fa-gift';
                        
                        $hexColor = 'var(--td-primary)';
                        if ($color === 'success') $hexColor = '#22C55E';
                        if ($color === 'danger') $hexColor = '#EF4444';
                        if ($color === 'warning') $hexColor = '#F59E0B';
                    @endphp
                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 text-lg shadow-sm"
                         style="background-color: {{ $hexColor }}1A;">
                        <i class="fa-solid {{ $faIcon }}" style="color: {{ $hexColor }};"></i>
                    </div>
                @endif

                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm {{ is_null($notification->read_at) ? 'font-bold' : '' }}"
                       style="color: var(--td-text);">{{ $data['title'] ?? 'Notification' }}</p>
                    <p class="text-sm mt-0.5" style="color: var(--td-muted);">{{ $data['body'] ?? $data['message'] ?? '' }}</p>
                    <p class="text-xs mt-1" style="color: var(--td-muted);">{{ $notification->created_at->diffForHumans() }}</p>
                </div>

                <div class="flex items-center gap-2 flex-shrink-0">
                    @if(is_null($notification->read_at))
                    <button wire:click="markRead('{{ $notification->id }}')"
                            class="text-xs px-2 py-1 rounded-lg font-medium transition-colors"
                            style="background: #DD66251A; color: var(--td-primary);">
                        Mark read
                    </button>
                    @endif
                    <button @click="confirmDelete('{{ $notification->id }}')"
                            class="w-7 h-7 rounded-full flex items-center justify-center text-red-400 hover:bg-red-50 transition-colors">
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $notifications->links() }}</div>
    @endif

    <!-- Alpine Custom Modal -->
    <div x-show="showConfirmModal"
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
                <i class="fa-solid fa-trash-can text-2xl"></i>
            </div>
            
            <h3 class="text-xl font-bold mb-2" style="color: var(--td-text);">Delete Notification?</h3>
            <p class="text-sm mb-6" style="color: var(--td-muted);">
                Are you sure you want to delete this notification? This action cannot be undone.
            </p>
            
            <div class="flex gap-3 w-full">
                <button @click="showConfirmModal = false" class="flex-1 py-2.5 rounded-xl font-bold bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors" style="color: var(--td-text);">
                    Cancel
                </button>
                <button @click="executeDelete()" class="flex-1 py-2.5 rounded-xl font-bold text-white shadow-md transition-transform hover:scale-105 bg-red-500 hover:bg-red-600">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>
