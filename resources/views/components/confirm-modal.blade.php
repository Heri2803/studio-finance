<div x-data="{
    open: false,
    title: 'Yakin Mau Hapus? 🥺🐾',
    message: 'Data ini akan dihapus permanen dari sistem.',
    confirmText: 'Ya, Hapus! 🗑️',
    cancelText: 'Batal',
    badgeText: 'KONFIRMASI HAPUS',
    mood: 'delete',
    targetForm: null,
    show(detail) {
        this.title = detail.title || 'Yakin Mau Hapus? 🥺🐾';
        this.message = detail.message || 'Data ini akan dihapus permanen dari sistem.';
        this.confirmText = detail.confirmText || 'Ya, Hapus! 🗑️';
        this.cancelText = detail.cancelText || 'Batal';
        this.badgeText = detail.badgeText || 'KONFIRMASI';
        this.mood = detail.mood || 'delete';
        this.targetForm = detail.targetForm || detail.form || null;
        this.open = true;
    },
    confirm() {
        if (this.targetForm) {
            if (typeof this.targetForm.submit === 'function') {
                this.targetForm.submit();
            } else {
                HTMLFormElement.prototype.submit.call(this.targetForm);
            }
        }
        this.open = false;
    }
}"
@open-confirm-modal.window="show($event.detail)"
x-show="open" 
x-cloak 
@keydown.escape.window="open = false" 
role="dialog"
aria-modal="true"
aria-labelledby="confirm-modal-title"
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
             :class="mood === 'worried' ? 'border-taby-yellow-border' : 'border-taby-rose-border'"
             class="relative transform overflow-hidden rounded-3xl bg-white p-4 sm:p-7 text-center shadow-pastel-lg border-3 sm:border-4 transition-all w-full max-w-[290px] sm:max-w-md">
            
            <!-- Close Icon (Top Right) -->
            <button @click="open = false" 
                    type="button"
                    aria-label="Tutup dialog konfirmasi"
                    class="absolute top-2.5 right-2.5 sm:top-3.5 sm:right-3.5 w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-cream-200 hover:bg-cream-300 text-taby-muted hover:text-taby-charcoal flex items-center justify-center font-bold text-xs sm:text-sm transition">
                ✕
            </button>

            <!-- Mascot Cat (Tearful/Sad for delete, Worried for status change) -->
            <div class="flex justify-center mb-2.5 sm:mb-4">
                <div :class="mood === 'worried' ? 'border-taby-yellow-border' : 'border-taby-rose-border'"
                     class="p-2 sm:p-3 rounded-full bg-cream-100 border-2 shadow-sm">
                    <div x-show="mood === 'worried'">
                        <x-cat-mascot mood="payroll_worried" size="sm" class="sm:hidden" />
                        <x-cat-mascot mood="payroll_worried" size="lg" class="hidden sm:inline-flex" />
                    </div>
                    <div x-show="mood !== 'worried'">
                        <x-cat-mascot mood="delete" size="sm" class="sm:hidden" />
                        <x-cat-mascot mood="delete" size="lg" class="hidden sm:inline-flex" />
                    </div>
                </div>
            </div>

            <!-- Action Badge Pill -->
            <div class="mb-1.5 sm:mb-2">
                <span x-text="badgeText"
                      :class="mood === 'worried' ? 'bg-taby-yellow text-taby-yellow-dark border-taby-yellow-border' : 'bg-taby-rose text-taby-rose-dark border-taby-rose-border'"
                      class="inline-block px-2 sm:px-3 py-0.5 rounded-full text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider border font-display">
                </span>
            </div>

            <!-- Title -->
            <h3 id="confirm-modal-title" 
                x-text="title"
                class="font-display font-extrabold text-sm sm:text-lg text-taby-charcoal mb-1.5 sm:mb-2 leading-snug">
            </h3>

            <!-- Message Box -->
            <div class="rounded-2xl bg-cream-100/80 border border-taby-border p-2.5 sm:p-3.5 mb-3.5 sm:mb-5">
                <p x-text="message" class="text-xs sm:text-sm font-semibold text-taby-charcoal leading-relaxed">
                </p>
            </div>

            <!-- Action Buttons: Cancel vs Confirm -->
            <div class="grid grid-cols-2 gap-2 sm:gap-3">
                <button @click="open = false" 
                        type="button" 
                        class="w-full inline-flex justify-center items-center rounded-xl sm:rounded-2xl border-2 border-taby-border bg-cream-200 hover:bg-cream-300 text-taby-charcoal px-3 py-2 sm:py-2.5 text-xs sm:text-sm font-bold transition shadow-sm active:translate-y-[1px]">
                    <span x-text="cancelText"></span>
                </button>

                <button @click="confirm()" 
                        type="button" 
                        :class="mood === 'worried' 
                            ? 'bg-taby-yellow hover:bg-taby-yellow-border text-taby-yellow-dark border-taby-yellow-border' 
                            : 'bg-taby-rose hover:bg-taby-rose-border text-taby-rose-dark border-taby-rose-border'"
                        class="w-full inline-flex justify-center items-center rounded-xl sm:rounded-2xl border-2 px-3 py-2 sm:py-2.5 text-xs sm:text-sm font-bold shadow-pastel hover:translate-y-[-1px] active:translate-y-[1px] transition">
                    <span x-text="confirmText"></span>
                </button>
            </div>
        </div>
    </div>
</div>
