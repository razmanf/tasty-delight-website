<div class="p-8 md:p-12">
    <h2 class="text-2xl font-bold mb-6" style="color: var(--td-text);">Send a Message</h2>
    
    @if($successMessage)
        <div class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800 flex items-start gap-3">
            <i class="fa-solid fa-circle-check mt-1"></i>
            <p>{{ $successMessage }}</p>
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold mb-2" style="color: var(--td-text);">First Name</label>
                <input type="text" wire:model.blur="first_name" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 focus:ring-[#DD6625] focus:border-[#DD6625] transition-all" style="color: var(--td-text);">
                @error('first_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold mb-2" style="color: var(--td-text);">Last Name</label>
                <input type="text" wire:model.blur="last_name" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 focus:ring-[#DD6625] focus:border-[#DD6625] transition-all" style="color: var(--td-text);">
                @error('last_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>
        
        <div>
            <label class="block text-sm font-bold mb-2" style="color: var(--td-text);">Email Address</label>
            <input type="text" wire:model.blur="email" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 focus:ring-[#DD6625] focus:border-[#DD6625] transition-all" style="color: var(--td-text);">
            @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold mb-2" style="color: var(--td-text);">Subject</label>
            <input type="text" wire:model.blur="subject" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 focus:ring-[#DD6625] focus:border-[#DD6625] transition-all" style="color: var(--td-text);">
            @error('subject') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold mb-2" style="color: var(--td-text);">Message</label>
            <textarea rows="4" wire:model.blur="message" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 focus:ring-[#DD6625] focus:border-[#DD6625] transition-all resize-y min-h-[120px]" style="color: var(--td-text);"></textarea>
            @error('message') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="td-btn-primary w-full justify-center py-3 flex items-center gap-2" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="submit">Send Message</span>
            <span wire:loading wire:target="submit"><i class="fa-solid fa-spinner fa-spin"></i> Sending...</span>
        </button>
    </form>
</div>
