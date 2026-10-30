@if(session('success'))
    @php
        $rawAction = session('action');
        $msg = strtolower(session('success'));

        // Auto-detect action if not explicitly given
        if (!$rawAction) {
            if (str_contains($msg, 'hapus') || str_contains($msg, 'batal') || str_contains($msg, 'delete')) {
                $action = 'delete';
            } elseif (str_contains($msg, 'update') || str_contains($msg, 'perbarui') || str_contains($msg, 'sesuai')) {
                $action = 'update';
            } else {
                $action = 'create';
            }
        } else {
            $action = $rawAction;
        }

        $modalConfig = match($action) {
            'create' => [
                'mood' => 'create',
                'title' => 'Hore! Berhasil Ditambahkan~ 😻✨',
                'badge' => 'CREATE SUCCESS',
                'badge_class' => 'bg-taby-mint text-taby-mint-dark border-taby-mint-border',
                'card_bg' => 'bg-white',
                'border_color' => 'border-taby-mint-border',
                'btn_bg' => 'bg-taby-mint hover:bg-taby-mint-border text-taby-mint-dark border-taby-mint-border',
                'btn_text' => 'Oke, Makasih! 🐾',
            ],
            'update' => [
                'mood' => 'update',
                'title' => 'Mantap! Data Berhasil Diperbarui~ ✏️💖',
                'badge' => 'UPDATE SUCCESS',
                'badge_class' => 'bg-taby-lilac text-taby-lilac-dark border-taby-lilac-border',
                'card_bg' => 'bg-white',
                'border_color' => 'border-taby-lilac-border',
                'btn_bg' => 'bg-taby-lilac hover:bg-taby-lilac-border text-taby-charcoal border-taby-lilac-border',
                'btn_text' => 'Siap, Lanjutkan! ✨',
            ],
            'delete' => [
                'mood' => 'delete',
                'title' => 'Dadah Data Lama~ 🐾🥺',
                'badge' => 'DELETE SUCCESS',
                'badge_class' => 'bg-taby-rose text-taby-rose-dark border-taby-rose-border',
                'card_bg' => 'bg-white',
                'border_color' => 'border-taby-rose-border',
                'btn_bg' => 'bg-taby-rose hover:bg-taby-rose-border text-taby-rose-dark border-taby-rose-border',
                'btn_text' => 'Siap, Mengerti~ 👋',
            ],
            default => [
                'mood' => 'create',
                'title' => 'Yay! Berhasil~ 🐾',
                'badge' => 'SUCCESS',
                'badge_class' => 'bg-taby-mint text-taby-mint-dark border-taby-mint-border',
                'card_bg' => 'bg-white',
                'border_color' => 'border-taby-border',
                'btn_bg' => 'bg-taby-yellow text-taby-charcoal border-taby-yellow-border',
                'btn_text' => 'Tutup 🐾',
            ],
        };
    @endphp

    <div x-data="{ open: true }" 
         x-show="open" 
         x-cloak 
         @keydown.escape.window="open = false" 
         role="dialog"
         aria-modal="true"
         aria-labelledby="notification-modal-title"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        
        <!-- Backdrop Blur Overlay -->
        <div x-show="open" 
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-taby-charcoal/50 backdrop-blur-sm transition-opacity" 
             @click="open = false"></div>

        <!-- Modal Card Container (Compact on Mobile!) -->
        <div class="flex min-h-full items-center justify-center p-3 text-center sm:p-4">
            <div x-show="open"
                 x-transition:enter="ease-out duration-250"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90"
                 class="relative transform overflow-hidden rounded-3xl {{ $modalConfig['card_bg'] }} p-4 sm:p-7 text-center shadow-pastel-lg border-3 sm:border-4 {{ $modalConfig['border_color'] }} transition-all w-full max-w-[280px] sm:max-w-md">
                
                <!-- Close Icon (Top Right) -->
                <button @click="open = false" 
                        type="button"
                        aria-label="Tutup notifikasi"
                        class="absolute top-2.5 right-2.5 sm:top-3.5 sm:right-3.5 w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-cream-200 hover:bg-cream-300 text-taby-muted hover:text-taby-charcoal flex items-center justify-center font-bold text-xs sm:text-sm transition">
                    ✕
                </button>

                <!-- Mascot Cat (Scaled down for mobile!) -->
                <div class="flex justify-center mb-2.5 sm:mb-4">
                    <div class="p-2 sm:p-3 rounded-full bg-cream-100 border-2 {{ $modalConfig['border_color'] }} shadow-sm">
                        <!-- Mobile Size (xs/sm) -->
                        <x-cat-mascot :mood="$modalConfig['mood']" size="sm" class="sm:hidden" />
                        <!-- Desktop Size (lg) -->
                        <x-cat-mascot :mood="$modalConfig['mood']" size="lg" class="hidden sm:inline-flex" />
                    </div>
                </div>

                <!-- Action Badge Pill -->
                <div class="mb-1.5 sm:mb-2">
                    <span class="inline-block px-2 sm:px-3 py-0.5 rounded-full text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider border {{ $modalConfig['badge_class'] }} font-display">
                        {{ $modalConfig['badge'] }}
                    </span>
                </div>

                <!-- Title -->
                <h3 id="notification-modal-title" class="font-display font-extrabold text-sm sm:text-lg text-taby-charcoal mb-1.5 sm:mb-2 leading-snug">
                    {{ $modalConfig['title'] }}
                </h3>

                <!-- Message Box -->
                <div class="rounded-2xl bg-cream-100/80 border border-taby-border p-2.5 sm:p-3.5 mb-3.5 sm:mb-5">
                    <p class="text-xs sm:text-sm font-semibold text-taby-charcoal leading-relaxed">
                        {{ session('success') }}
                    </p>
                </div>

                <!-- Action Button -->
                <div>
                    <button @click="open = false" 
                            type="button" 
                            class="w-full inline-flex justify-center items-center gap-1.5 rounded-xl sm:rounded-2xl border-2 px-4 py-2 sm:py-2.5 text-xs sm:text-sm font-bold shadow-pastel hover:translate-y-[-1px] active:translate-y-[1px] transition {{ $modalConfig['btn_bg'] }}">
                        {{ $modalConfig['btn_text'] }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
