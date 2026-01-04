@php
    $toasts = [];

    if (session('success')) {
        $toasts[] = [
            'id' => uniqid('t_'),
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => session('success'),
            'duration' => 4200,
        ];
    }

    if (session('error')) {
        $toasts[] = [
            'id' => uniqid('t_'),
            'type' => 'error',
            'title' => 'Gagal',
            'message' => session('error'),
            'duration' => 5200,
        ];
    }

    // ambil error pertama aja biar ga rame
    if ($errors->any()) {
        $toasts[] = [
            'id' => uniqid('t_'),
            'type' => 'error',
            'title' => 'Gagal',
            'message' => $errors->first(),
            'duration' => 6500,
        ];
    }
@endphp

@if (count($toasts) > 0)
<div
    x-data="{
        toasts: @js($toasts),

        removeById(id){
            this.toasts = this.toasts.filter(t => t.id !== id)
        },

        init(){
            // Auto dismiss per toast (pakai id biar aman walau array berubah)
            this.toasts.forEach((t, idx) => {
                setTimeout(() => {
                    this.removeById(t.id)
                }, (t.duration || 4000) + (idx * 120));
            });
        }
    }"
    x-init="init()"
    class="fixed top-4 right-4 z-[9999] space-y-3 w-[92vw] max-w-sm"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transform transition ease-out duration-250"
            x-transition:enter-start="opacity-0 translate-x-6 scale-[0.98]"
            x-transition:enter-end="opacity-100 translate-x-0 scale-100"
            x-transition:leave="transform transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0 scale-100"
            x-transition:leave-end="opacity-0 translate-x-6 scale-[0.98]"
            class="relative overflow-hidden rounded-2xl border border-black/5 bg-white/90 backdrop-blur shadow-[0_10px_30px_-15px_rgba(0,0,0,0.35)]"
        >
            <!-- Progress bar -->
            <div class="absolute left-0 top-0 h-[3px] w-full bg-black/5">
                <div
                    class="h-full"
                    :class="toast.type === 'success' ? 'bg-emerald-500' : 'bg-rose-500'"
                    :style="`animation: toast-progress ${toast.duration || 4000}ms linear forwards;`"
                ></div>
            </div>

            <div class="px-4 py-3 flex items-start gap-3">
                <!-- Icon badge -->
                <div
                    class="mt-0.5 grid place-items-center w-9 h-9 rounded-xl shrink-0"
                    :class="toast.type === 'success'
                        ? 'bg-emerald-500/10 text-emerald-600'
                        : 'bg-rose-500/10 text-rose-600'"
                >
                    <svg x-show="toast.type === 'success'" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>

                    <svg x-show="toast.type !== 'success'" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86l-7.5 13A1.5 1.5 0 004.09 19h15.82a1.5 1.5 0 001.3-2.14l-7.5-13a1.5 1.5 0 00-2.6 0z"/>
                    </svg>
                </div>

                <!-- Text -->
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold text-gray-900" x-text="toast.title || (toast.type === 'success' ? 'Berhasil' : 'Gagal')"></div>
                    <div class="text-sm text-gray-600 leading-snug break-words" x-text="toast.message"></div>
                </div>

                <!-- Close -->
                <button
                    type="button"
                    class="text-gray-400 hover:text-gray-700 transition rounded-lg px-2 py-1"
                    @click="removeById(toast.id)"
                    aria-label="Close"
                >
                    ✕
                </button>
            </div>

            <!-- Subtle left accent -->
            <div
                class="absolute left-0 top-0 h-full w-[4px]"
                :class="toast.type === 'success' ? 'bg-emerald-500' : 'bg-rose-500'"
            ></div>
        </div>
    </template>

    <style>
        @keyframes toast-progress {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>
</div>
@endif
