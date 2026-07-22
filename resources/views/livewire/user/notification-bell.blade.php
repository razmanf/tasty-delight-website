<div class="relative" x-data="{ notifOpen: false }" @click.outside="notifOpen = false" wire:poll.10s>
    <button @click="notifOpen = !notifOpen" class="relative text-white/80 hover:text-white transition-colors p-2">
        <i class="fa-regular fa-bell text-lg"></i>
        @if($this->unreadCount > 0)
            <span class="td-notif-badge">{{ $this->unreadCount > 99 ? '99+' : $this->unreadCount }}</span>
        @endif
    </button>
    
    <div x-show="notifOpen"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 rounded-xl shadow-xl border overflow-hidden z-50"
         style="background-color: var(--td-bg); border-color: var(--td-border); display: none;">
        
        <div class="px-4 py-3 border-b flex justify-between items-center" style="border-color: var(--td-border);">
            <p class="text-sm font-bold" style="color: var(--td-text);">Notifications</p>
            @if($this->unreadCount > 0)
                <span class="text-xs font-bold text-white px-2 py-0.5 rounded-full" style="background-color: var(--td-danger);">{{ $this->unreadCount }} New</span>
            @endif
        </div>

        <div class="max-h-80 overflow-y-auto">
            @if($this->recentNotifs->isEmpty())
                <div class="px-4 py-6 text-center">
                    <i class="fa-regular fa-bell-slash text-2xl mb-2" style="color: var(--td-muted);"></i>
                    <p class="text-sm" style="color: var(--td-muted);">You're all caught up!</p>
                </div>
            @else
                @foreach($this->recentNotifs as $notif)
                    <a href="{{ route('user.notifications') }}" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors border-b last:border-0" style="border-color: var(--td-border);">
                        <div class="flex gap-3 items-start">
                            @php
                                $icon = $notif->data['icon'] ?? 'heroicon-o-bell';
                                $color = $notif->data['color'] ?? 'primary';
                                
                                // Mapping filament icons to font-awesome for UI
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
                            <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 shadow-sm" style="background-color: {{ $hexColor }}1A;">
                                <i class="fa-solid {{ $faIcon }}" style="color: {{ $hexColor }};"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold truncate" style="color: var(--td-text);">{{ $notif->data['title'] ?? 'Notification' }}</p>
                                <p class="text-xs line-clamp-2 mt-0.5" style="color: var(--td-muted);">{{ $notif->data['body'] ?? $notif->data['message'] ?? 'You have a new update.' }}</p>
                                <p class="text-[10px] mt-1 uppercase font-bold" style="color: var(--td-muted);">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                            @if(is_null($notif->read_at))
                                <div class="w-2 h-2 rounded-full flex-shrink-0 mt-1.5" style="background-color: var(--td-primary);"></div>
                            @endif
                        </div>
                    </a>
                @endforeach
            @endif
        </div>

        <div class="border-t p-2 bg-gray-50 dark:bg-gray-900/50" style="border-color: var(--td-border);">
            <a href="{{ route('user.notifications') }}" class="block w-full text-center text-xs font-bold py-1.5 hover:underline" style="color: var(--td-primary);">
                View all notifications &rarr;
            </a>
        </div>
    </div>
</div>
