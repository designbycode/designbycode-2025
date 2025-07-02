<div x-data="{
    copied: false,
    resetCopied() {
        this.copied = true;
        setTimeout(() => {
            this.copied = false;
        }, 2000);
    }
}"
     class="w-full rounded-lg my-4 bg-foreground/5 backdrop-blur-sm flex flex-col p-2 border border-foreground/10 shadow-md shadow-black/25">
    <div class="flex justify-between items-center text-xs p-2">
        <span class="tracking-wide">{{ strtoupper($language) }}</span>
        @if($showCopy ?? true)
            <button
                x-clipboard="{{ json_encode($code ?? trim($slot)) }}"
                x-on:click="resetCopied()"
                class="text-xs px-2 py-1 rounded bg-foreground/5 hover:bg-background transition"
                type="button"
            >
                <span x-show="!copied">COPY</span>
                <span x-show="copied" class="text-green-500">COPIED!</span>
            </button>
        @endif
    </div>
    {{-- @formatter:off --}}
<pre class="border border-foreground/10 text-xs md:text-md rounded-lg p-2 flex-1 overflow-x-auto no-scrollbar-track"><code :class="`language-{{ $language }}`" x-text="{{json_encode($code ?? trim($slot)) }}"></code></pre>
    {{-- @formatter:on --}}
</div>
