@props([
    'for' => null,
    'fieldName' => null,
    'fieldLabel' => null,
    'pageContext' => 'Admin Content',
    'sectionContext' => 'Form Field',
    'imageSelector' => null,
    'imagePath' => null,
    'siblingSelectors' => [],
    'size' => 'sm',
])

@php
    $targetId = $for ?? $fieldName;
    $computedLabel = $fieldLabel ?? ($fieldName ? ucfirst(str_replace('_', ' ', $fieldName)) : 'Field');
@endphp

<div 
    x-data="{
        open: false,
        loading: false,
        generatedText: '',
        error: '',
        tweak: '',
        targetId: '{{ $targetId }}',
        fieldName: '{{ $fieldName ?? $targetId }}',
        fieldLabel: '{{ $computedLabel }}',
        pageContext: '{{ $pageContext }}',
        sectionContext: '{{ $sectionContext }}',
        imageSelector: '{{ $imageSelector }}',
        staticImagePath: '{{ $imagePath }}',
        siblingSelectors: {{ json_encode($siblingSelectors) }},
        justInserted: false,

        getTargetElement() {
            if (!this.targetId) return null;
            return document.getElementById(this.targetId) || document.querySelector(`[name='${this.targetId}']`);
        },

        resolveImagePath() {
            if (this.staticImagePath) return this.staticImagePath;
            if (this.imageSelector) {
                const el = document.querySelector(this.imageSelector);
                if (el) {
                    if (el.tagName === 'INPUT') return el.value || null;
                    if (el.tagName === 'IMG') {
                        const src = el.getAttribute('src');
                        return src ? src.replace(/^https?:\/\/[^\/]+/, '') : null;
                    }
                }
            }
            return null;
        },

        resolveSiblingData() {
            const data = {};
            if (this.siblingSelectors && typeof this.siblingSelectors === 'object') {
                for (const [key, selector] of Object.entries(this.siblingSelectors)) {
                    const el = document.querySelector(selector);
                    if (el && el.value) {
                        data[key] = el.value;
                    }
                }
            }
            return data;
        },

        toggle() {
            if (this.open) {
                this.open = false;
            } else {
                this.open = true;
                this.error = '';
                // Automatically generate if empty
                if (!this.generatedText) {
                    this.generate();
                }
            }
        },

        generate() {
            this.loading = true;
            this.error = '';

            const payload = {
                field_name: this.fieldName,
                field_label: this.fieldLabel,
                page_context: this.pageContext,
                section_context: this.sectionContext,
                sibling_data: this.resolveSiblingData(),
                prompt_tweak: this.tweak,
                image_path: this.resolveImagePath()
            };

            fetch('{{ route('admin.ai.generate') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.text) {
                    this.generatedText = data.text;
                } else {
                    this.error = data.error || 'Failed to generate text. Please check your Gemini settings.';
                }
            })
            .catch(err => {
                this.error = 'Network error contacting server. Please try again.';
            })
            .finally(() => {
                this.loading = false;
            });
        },

        insertText() {
            const el = this.getTargetElement();
            if (el && this.generatedText) {
                el.value = this.generatedText;
                el.dispatchEvent(new Event('input', { bubbles: true }));
                el.dispatchEvent(new Event('change', { bubbles: true }));
                this.justInserted = true;
                setTimeout(() => { this.justInserted = false; }, 2000);
            }
            this.open = false;
        }
    }"
    class="relative inline-block text-left"
>
    <!-- Trigger Button -->
    <button 
        type="button" 
        @click="toggle()" 
        class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg text-[11px] font-semibold transition-all duration-150 border cursor-pointer select-none"
        :class="justInserted 
            ? 'bg-emerald-50 text-emerald-700 border-emerald-300' 
            : 'bg-purple-50 text-purple-700 hover:bg-purple-100 hover:text-purple-900 border-purple-200/80 shadow-2xs hover:shadow-xs'"
        title="Generate or refine text using Google Gemini AI"
    >
        <template x-if="!justInserted">
            <svg class="w-3 h-3 text-purple-600 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
            </svg>
        </template>
        <template x-if="justInserted">
            <svg class="w-3 h-3 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
        </template>
        <span x-text="justInserted ? 'Inserted!' : 'AI Write'"></span>
    </button>

    <!-- Floating Preview Popover -->
    <div 
        x-show="open" 
        x-cloak
        @click.outside="open = false" 
        @keydown.escape.window="open = false"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
        class="absolute z-50 left-0 mt-2 w-80 sm:w-96 p-4 bg-white rounded-2xl shadow-xl border border-stone-200 text-stone-800 space-y-3.5"
        style="max-width: 90vw;"
    >
        <!-- Popover Header -->
        <div class="flex items-center justify-between border-b border-stone-100 pb-2.5">
            <div class="flex items-center gap-2">
                <span class="p-1 rounded-md bg-purple-100 text-purple-700">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                    </svg>
                </span>
                <div>
                    <h4 class="text-xs font-bold text-stone-900" x-text="`Gemini Suggestion for ${fieldLabel}`"></h4>
                    <p class="text-[10px] text-stone-400" x-text="`${pageContext} • ${sectionContext}`"></p>
                </div>
            </div>
            <button 
                type="button" 
                @click="open = false" 
                class="text-stone-400 hover:text-stone-600 rounded-lg p-1 hover:bg-stone-100 cursor-pointer"
            >
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="py-6 flex flex-col items-center justify-center space-y-2.5 text-center">
            <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-purple-50 text-purple-600 animate-spin">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
            </div>
            <p class="text-xs font-medium text-stone-600">Drafting text with Google Gemini...</p>
            <p class="text-[10px] text-stone-400">Analyzing context & applying British English charity voice</p>
        </div>

        <!-- Error State -->
        <div x-show="!loading && error" class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
            <p class="font-bold flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-rose-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span>Generation Error</span>
            </p>
            <p class="text-[11px]" x-text="error"></p>
            <div class="pt-1.5">
                <button 
                    type="button" 
                    @click="generate()" 
                    class="px-2.5 py-1 bg-white border border-rose-300 rounded-lg text-[11px] font-semibold text-rose-700 hover:bg-rose-100 cursor-pointer"
                >
                    Retry Generation
                </button>
            </div>
        </div>

        <!-- Result Preview & Editable Area -->
        <div x-show="!loading && !error && generatedText" class="space-y-3">
            <div class="relative">
                <textarea 
                    x-model="generatedText" 
                    rows="4" 
                    class="w-full p-2.5 rounded-xl border border-stone-200 text-xs text-stone-800 bg-stone-50/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all leading-relaxed"
                    placeholder="Generated text will appear here..."
                ></textarea>
                <span class="absolute bottom-2 right-2 text-[10px] text-stone-400 bg-white/80 px-1 rounded" x-text="`${generatedText.length} chars`"></span>
            </div>

            <!-- Tweak Input -->
            <div class="flex items-center gap-1.5 pt-1">
                <div class="relative flex-1">
                    <input 
                        type="text" 
                        x-model="tweak" 
                        @keydown.enter.prevent="generate()"
                        placeholder="Tweak instruction (e.g. 'Make it shorter')..."
                        class="w-full pl-3 pr-8 py-1.5 rounded-xl border border-stone-200 text-xs focus:outline-none focus:ring-1 focus:ring-purple-500 bg-white"
                    >
                    <button 
                        type="button" 
                        @click="generate()" 
                        class="absolute right-2 top-1/2 -translate-y-1/2 text-purple-600 hover:text-purple-800 cursor-pointer"
                        title="Regenerate with tweak"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="flex items-center justify-between pt-1 border-t border-stone-100">
                <button 
                    type="button" 
                    @click="generate()" 
                    class="inline-flex items-center gap-1 text-[11px] font-semibold text-stone-500 hover:text-purple-700 hover:bg-purple-50 px-2 py-1 rounded-lg transition-colors cursor-pointer"
                    title="Generate another variation"
                >
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Regenerate</span>
                </button>

                <div class="flex items-center gap-2">
                    <button 
                        type="button" 
                        @click="open = false" 
                        class="px-3 py-1.5 rounded-xl text-xs font-medium text-stone-600 hover:bg-stone-100 transition-colors cursor-pointer"
                    >
                        Cancel
                    </button>
                    <button 
                        type="button" 
                        @click="insertText()" 
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-maroon text-white hover:bg-crimson transition-all shadow-xs cursor-pointer flex items-center gap-1.5"
                    >
                        <svg class="w-3.5 h-3.5 text-cream" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Insert into Field</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
