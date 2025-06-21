<div x-data="{ copied: false }"
     class="w-full rounded-lg my-4 bg-gray-950/75 backdrop-blur-md flex flex-col p-2 border border-gray-800/50 shadow-md shadow-black/25">
    <div class="flex justify-between text-xs p-2">
        <span class="tracking-wide">{{ strtoupper($language) }}</span>
        @if($showCopy ?? true)
            <button
                x-data
                x-clipboard.raw="test"
                @clipboard:success="copied = true; setTimeout(() => { copied = false }, 2000)"
                class="text-xs px-2 py-1 rounded bg-gray-800 hover:bg-gray-700 transition"
                type="button"
            >
                <span x-show="!copied">COPY</span>
                <span x-show="copied" class="text-green-400">COPIED!</span>
            </button>
        @endif
    </div>
    {{-- @formatter:off --}}
<pre class="border border-gray-800/50 rounded-lg p-2 min-h-full flex-1 overflow-x-auto no-scrollbar-track">
<code :class="`language-{{ $language }}`" x-text="{{ json_encode($code ?? trim($slot)) }}"></code>
</pre>
    {{-- @formatter:on --}}
</div>
