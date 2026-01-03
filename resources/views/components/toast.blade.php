@php
    $toasts = [];

    if (session('success')) {
        $toasts[] = ['type' => 'success', 'message' => session('success')];
    }

    if (session('error')) {
        $toasts[] = ['type' => 'error', 'message' => session('error')];
    }

    // Ambil error pertama aja biar ga rame
    if ($errors->any()) {
        $toasts[] = ['type' => 'error', 'message' => $errors->first()];
    }
@endphp

@if(count($toasts) > 0)
<div
    x-data="{
        toasts: @js($toasts),
        remove(i){ this.toasts.splice(i, 1) },
        init(){
            // auto dismiss
            this.toasts.forEach((t, idx) => {
                setTimeout(() => {
                    // kalau masih ada index tsb
                    if (this.toasts[idx]) this.remove(idx);
                }, 3500 + (idx * 250));
            });
        }
    }"
    x-init="init()"
    class="fixed top-4 right-4 z-[9999] space-y-3 w-[92vw] max-w-sm"
>
    <template x-for="(toast, i) in toasts" :key="i">
        <div
            x-show="toast"
            x-transition.opacity.duration.200ms
            class="rounded-2xl border shadow-lg bg-white overflow-hidden"
        >
            <div
                class="px-4 py-3 flex items-start gap-3"
                :class="toast.type === 'success' ? 'border-l-4 border-green-500' : 'border-l-4 border-red-500'"
            >
                <div class="mt-0.5">
                    <span x-text="toast.type === 'success' ? '✅' : '⚠️'"></span>
                </div>

                <div class="flex-1">
                    <div class="text-sm font-semibold"
                         x-text="toast.type === 'success' ? 'Berhasil' : 'Gagal'"></div>
                    <div class="text-sm text-gray-600" x-text="toast.message"></div>
                </div>

                <button
                    class="text-gray-400 hover:text-gray-700 transition"
                    @click="remove(i)"
                    aria-label="Close"
                >✕</button>
            </div>
        </div>
    </template>
</div>
@endif
