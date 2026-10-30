@props([
    'target',
    'compact' => false,
])

<div x-data="{
    targetEl: null,
    previewOpen: false,
    previewHtml: '',
    init() {
        this.targetEl = document.querySelector('{{ $target }}');
        if (this.targetEl) {
            this.updatePreview();
            this.targetEl.addEventListener('input', () => this.updatePreview());
        }
    },
    wrap(tag) {
        if (!this.targetEl) {
            this.targetEl = document.querySelector('{{ $target }}');
        }
        if (!this.targetEl) return;

        const start = this.targetEl.selectionStart;
        const end = this.targetEl.selectionEnd;
        const value = this.targetEl.value;
        const selected = value.substring(start, end);
        const insertText = selected || 'text';
        const wrapped = `[${tag}]${insertText}[/${tag}]`;

        this.targetEl.focus();
        if (typeof this.targetEl.setRangeText === 'function') {
            this.targetEl.setRangeText(wrapped, start, end, 'select');
        } else {
            this.targetEl.value = value.substring(0, start) + wrapped + value.substring(end);
        }

        // Adjust selection to highlight the inner text
        const newStart = start + tag.length + 2;
        const newEnd = newStart + insertText.length;
        this.targetEl.setSelectionRange(newStart, newEnd);

        this.targetEl.dispatchEvent(new Event('input', { bubbles: true }));
        this.updatePreview();
    },
    updatePreview() {
        if (!this.targetEl) return;
        let text = this.targetEl.value || '';
        // Escape HTML
        let escaped = text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/&quot;/g, '&quot;')
            .replace(/'/g, '&#039;');
        
        // Tags
        escaped = escaped.replace(/\[accent\](.*?)\[\/accent\]/gis, '<span class=\'text-maroon font-semibold\'>$1</span>');
        escaped = escaped.replace(/\[crimson\](.*?)\[\/crimson\]/gis, '<span class=\'text-rose-600 font-bold\'>$1</span>');
        escaped = escaped.replace(/\[mint\](.*?)\[\/mint\]/gis, '<span class=\'text-emerald-600 font-bold\'>$1</span>');
        escaped = escaped.replace(/\[cream\](.*?)\[\/cream\]/gis, '<span class=\'bg-amber-100 text-amber-900 px-1 rounded font-bold\'>$1</span>');
        escaped = escaped.replace(/\[maroon\](.*?)\[\/maroon\]/gis, '<span class=\'text-red-900 font-bold\'>$1</span>');
        escaped = escaped.replace(/\[gold\](.*?)\[\/gold\]/gis, '<span class=\'text-amber-600 font-bold\'>$1</span>');
        escaped = escaped.replace(/\[bold\](.*?)\[\/bold\]/gis, '<strong class=\'font-bold text-stone-900\'>$1</strong>');
        escaped = escaped.replace(/\[italic\](.*?)\[\/italic\]/gis, '<em class=\'italic\'>$1</em>');
        escaped = escaped.replace(/\[underline\](.*?)\[\/underline\]/gis, '<span class=\'underline decoration-rose-500/60 underline-offset-4 font-semibold\'>$1</span>');
        escaped = escaped.replace(/\[badge\](.*?)\[\/badge\]/gis, '<span class=\'inline-block px-2 py-0.5 rounded-full text-xs font-bold bg-maroon/10 text-maroon border border-maroon/20\'>$1</span>');
        escaped = escaped.replace(/\n/g, '<br>');

        this.previewHtml = escaped;
    }
}" class="inline-flex flex-col gap-1.5 w-full">
    {{-- Inline Toolbar --}}
    <div class="flex flex-wrap items-center justify-between gap-1.5 py-1">
        <div class="flex flex-wrap items-center gap-1 text-[11px]">
            <span class="text-[10px] font-bold uppercase tracking-wider text-stone-400 mr-1 select-none">Style:</span>
            
            <button type="button" @click="wrap('accent')" 
                    class="px-2 py-0.5 rounded-md bg-amber-50 hover:bg-amber-100 text-maroon border border-amber-200 font-semibold cursor-pointer transition-colors shadow-2xs" 
                    title="Wrap in Brand Accent ([accent]...[/accent])">
                ✦ Accent
            </button>

            <button type="button" @click="wrap('crimson')" 
                    class="px-2 py-0.5 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold cursor-pointer transition-colors shadow-2xs" 
                    title="Wrap in Crimson Red ([crimson]...[/crimson])">
                ● Crimson
            </button>

            <button type="button" @click="wrap('mint')" 
                    class="px-2 py-0.5 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 font-bold cursor-pointer transition-colors shadow-2xs" 
                    title="Wrap in Mint Green ([mint]...[/mint])">
                ● Mint
            </button>

            <button type="button" @click="wrap('cream')" 
                    class="px-2 py-0.5 rounded-md bg-amber-100/60 hover:bg-amber-100 text-amber-900 border border-amber-300 font-bold cursor-pointer transition-colors shadow-2xs" 
                    title="Wrap in Cream ([cream]...[/cream])">
                ● Cream
            </button>

            <button type="button" @click="wrap('gold')" 
                    class="px-2 py-0.5 rounded-md bg-yellow-50 hover:bg-yellow-100 text-yellow-800 border border-yellow-200 font-bold cursor-pointer transition-colors shadow-2xs" 
                    title="Wrap in Gold ([gold]...[/gold])">
                ● Gold
            </button>

            <button type="button" @click="wrap('bold')" 
                    class="px-2 py-0.5 rounded-md bg-stone-100 hover:bg-stone-200 text-stone-900 border border-stone-300 font-extrabold cursor-pointer transition-colors shadow-2xs" 
                    title="Bold ([bold]...[/bold])">
                B
            </button>

            <button type="button" @click="wrap('italic')" 
                    class="px-2 py-0.5 rounded-md bg-stone-100 hover:bg-stone-200 text-stone-800 border border-stone-300 italic cursor-pointer transition-colors shadow-2xs" 
                    title="Italic ([italic]...[/italic])">
                I
            </button>

            <button type="button" @click="wrap('underline')" 
                    class="px-2 py-0.5 rounded-md bg-stone-100 hover:bg-stone-200 text-stone-800 border border-stone-300 underline cursor-pointer transition-colors shadow-2xs" 
                    title="Underline ([underline]...[/underline])">
                U
            </button>

            <button type="button" @click="wrap('badge')" 
                    class="px-2 py-0.5 rounded-md bg-maroon/10 hover:bg-maroon/20 text-maroon border border-maroon/20 font-bold cursor-pointer transition-colors shadow-2xs" 
                    title="Pill Badge ([badge]...[/badge])">
                [Badge]
            </button>
        </div>

        {{-- Preview Toggle --}}
        <button type="button" @click="previewOpen = !previewOpen" 
                class="inline-flex items-center gap-1 text-[11px] font-semibold text-stone-500 hover:text-stone-800 cursor-pointer">
            <span x-text="previewOpen ? 'Hide Preview' : '👁️ Preview'"></span>
        </button>
    </div>

    {{-- Live Preview Box --}}
    <div x-show="previewOpen" x-cloak 
         class="p-3 rounded-xl bg-stone-50 border border-stone-200 text-xs text-stone-700 leading-relaxed shadow-inner transition-all">
        <span class="text-[10px] font-bold uppercase tracking-wider text-stone-400 block mb-1">Live Styled Output Preview:</span>
        <div x-html="previewHtml || '<span class=\'text-stone-400 italic\'>Type text with tags above to see preview</span>'"></div>
    </div>
</div>
