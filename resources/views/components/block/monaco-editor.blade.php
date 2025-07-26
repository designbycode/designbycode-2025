{{-- resources/views/components/monaco-editor.blade.php --}}
<div x-data="{
        monacoContent: @js($content),
        monacoLanguage: @js($language),
        monacoPlaceholder: {{ $content ? 'false' : 'true' }},
        monacoPlaceholderText: @js($placeholder),
        monacoLoader: true,
        monacoFontSize: @js($fontSize),
        monacoId: @js($id),
        monacoEditable: @js($editable),
        monacoTheme: @js($theme),
        showCopyButton: @js($showCopyButton),
        copySuccess: false,
        editorHeight: @js($minHeight),

        monacoEditor(editor){
            editor.onDidChangeModelContent((e) => {
                this.monacoContent = editor.getValue();
                this.updatePlaceholder(editor.getValue());
                this.updateEditorHeight(editor);
                // Emit custom event for parent components
                this.$dispatch('monaco-content-changed', { content: editor.getValue() });
            });

            editor.onDidBlurEditorWidget(() => {
                this.updatePlaceholder(editor.getValue());
            });

            editor.onDidFocusEditorWidget(() => {
                this.updatePlaceholder(editor.getValue());
            });
        },

        updatePlaceholder: function(value) {
            if (value == '') {
                this.monacoPlaceholder = true;
                return;
            }
            this.monacoPlaceholder = false;
        },

        updateEditorHeight: function(editor) {
            const lineHeight = editor.getOption(monaco.editor.EditorOption.lineHeight);
            const lineCount = editor.getModel().getLineCount();
            const minLines = Math.ceil(parseInt(@js($minHeight)) / lineHeight);
            const actualLines = Math.max(lineCount, minLines);
            const newHeight = actualLines * lineHeight + 40; // Add padding for UI elements

            this.editorHeight = newHeight + 'px';
            editor.layout();
        },

        monacoEditorFocus(){
            document.getElementById(this.monacoId).dispatchEvent(new CustomEvent('monaco-editor-focused', { monacoId: this.monacoId }));
        },

        monacoEditorAddLoaderScriptToHead() {
            script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.39.0/min/vs/loader.min.js';
            document.head.appendChild(script);
        },

        copyToClipboard() {
            // Get the current content from the Monaco editor instance
            const editor = document.getElementById(this.monacoId).editor;
            const editorContent = editor ? editor.getValue() : this.monacoContent;

            navigator.clipboard.writeText(editorContent).then(() => {
                this.copySuccess = true;
                setTimeout(() => {
                    this.copySuccess = false;
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
                // Fallback for older browsers
                try {
                    const textArea = document.createElement('textarea');
                    textArea.value = editorContent;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    this.copySuccess = true;
                    setTimeout(() => {
                        this.copySuccess = false;
                    }, 2000);
                } catch (fallbackErr) {
                    console.error('Fallback copy failed: ', fallbackErr);
                }
            });
        }
    }"
     x-init="
        if(typeof _amdLoaderGlobal == 'undefined'){
            monacoEditorAddLoaderScriptToHead();
        }

        monacoLoaderInterval = setInterval(function(){
            if(typeof _amdLoaderGlobal !== 'undefined'){
                require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.39.0/min/vs' }});
                let proxy = URL.createObjectURL(new Blob([` self.MonacoEnvironment = { baseUrl: 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.39.0/min' }; importScripts('https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.39.0/min/vs/base/worker/workerMain.min.js');`], { type: 'text/javascript' }));
                window.MonacoEnvironment = { getWorkerUrl: () => proxy };

                require(['vs/editor/editor.main'], function() {
                    // Define the blackboard theme with updated background
                    monacoTheme = {'base':'vs-dark','inherit':true,'rules':[{'background':'0C1021BF','token':''},{'foreground':'aeaeae','token':'comment'},
                    {'foreground':'d8fa3c','token':'constant'},{'foreground':'ff6400','token':'entity'},{'foreground':'fbde2d','token':'keyword'},
                    {'foreground':'fbde2d','token':'storage'},{'foreground':'61ce3c','token':'string'},{'foreground':'61ce3c','token':'meta.verbatim'},
                    {'foreground':'8da6ce','token':'support'},{'foreground':'ab2a1d','fontStyle':'italic','token':'invalid.deprecated'},{'foreground':'f8f8f8','background':'9d1e15','token':'invalid.illegal'},{'foreground':'ff6400','fontStyle':'italic','token':'entity.other.inherited-class'},{'foreground':'ff6400','token':'string constant.other.placeholder'},{'foreground':'becde6','token':'meta.function-call.py'},{'foreground':'7f90aa','token':'meta.tag'},{'foreground':'7f90aa','token':'meta.tag entity'},{'foreground':'ffffff','token':'entity.name.section'},{'foreground':'d5e0f3','token':'keyword.type.variant'},{'foreground':'f8f8f8','token':'source.ocaml keyword.operator.symbol'},{'foreground':'8da6ce','token':'source.ocaml keyword.operator.symbol.infix'},{'foreground':'8da6ce','token':'source.ocaml keyword.operator.symbol.prefix'},{'fontStyle':'underline','token':'source.ocaml keyword.operator.symbol.infix.floating-point'},{'fontStyle':'underline','token':'source.ocaml keyword.operator.symbol.prefix.floating-point'},{'fontStyle':'underline','token':'source.ocaml constant.numeric.floating-point'},{'background':'ffffff08','token':'text.tex.latex meta.function.environment'},{'background':'7a96fa08','token':'text.tex.latex meta.function.environment meta.function.environment'},{'foreground':'fbde2d','token':'text.tex.latex support.function'},{'foreground':'ffffff','token':'source.plist string.unquoted'},{'foreground':'ffffff','token':'source.plist keyword.operator'}],'colors':{'editor.foreground':'#F8F8F8','editor.background':'#0d0d0e','editor.selectionBackground':'#253B76','editor.lineHighlightBackground':'#FFFFFF0F','editorCursor.foreground':'#FFFFFFA6','editorWhitespace.foreground':'#FFFFFF40'}};
                    monaco.editor.defineTheme('blackboard', monacoTheme);

                    const editor = monaco.editor.create($refs.monacoEditorElement, {
                        value: monacoContent,
                        theme: 'blackboard',
                        fontSize: monacoFontSize,
                        lineNumbersMinChars: 2,
                        automaticLayout: true,
                        language: monacoLanguage,
                        readOnly: !monacoEditable,
                        scrollBeyondLastLine: false,
                        minimap: { enabled: false },
                        contextmenu: monacoEditable,
                        wordWrap: 'on',
                        lineNumbers: 'on',
                        folding: true,
                        renderLineHighlight: 'line'
                    });

                    document.getElementById(monacoId).editor = editor;
                    monacoEditor(editor);

                    // Initial height calculation
                    updateEditorHeight(editor);

                    document.getElementById(monacoId).addEventListener('monaco-editor-focused', function(event){
                        editor.focus();
                    });

                    updatePlaceholder(editor.getValue());
                });

                clearInterval(monacoLoaderInterval);
                monacoLoader = false;
            }
        }, 5);
    "
     :id="monacoId"
     class="relative w-full bg-gray-950/75 rounded-lg overflow-hidden"
     :style="'height: ' + editorHeight + '; min-height: ' + @js($minHeight)"
>
    {{-- Copy Button --}}
    <div x-show="showCopyButton && !monacoLoader" class="absolute top-2 right-2 z-30">
        <button
            @click="copyToClipboard()"
            class="px-2 py-1 bg-white/5 hover:bg-gray-700 text-gray-300 text-xs rounded transition-colors duration-200 flex items-center gap-1.5"
            :class="{ 'bg-green-600/90 hover:bg-green-600': copySuccess }"
        >
            <span x-show="!copySuccess">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
            </span>
            <span x-show="copySuccess">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </span>
            <span x-text="copySuccess ? 'Copied!' : 'Copy'" class="text-xs"></span>
        </button>
    </div>

    {{-- Language Badge --}}
    <div x-show="!monacoLoader" class="absolute top-1 left-2 z-30">
        <span class="px-2 py-1 bg-white/5 text-gray-300 text-xs rounded font-mono uppercase" x-text="monacoLanguage"></span>
    </div>

    {{-- Loading Spinner --}}
    <div x-show="monacoLoader" class="absolute inset-0 z-20 flex items-center justify-center w-full h-full duration-1000 ease-out">
        <svg class="w-8 h-8 text-gray-400 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>

    {{-- Editor Container --}}
    <div x-show="!monacoLoader" class="relative w-full h-full pt-10">
        <div x-ref="monacoEditorElement" class="w-full h-full opacity-75 pt-2"></div>
        {{-- Placeholder Text --}}
        <div
            x-show="monacoPlaceholder && monacoEditable"
            @click="monacoEditorFocus()"
            :style="'font-size: ' + monacoFontSize"
            class="absolute z-50 text-gray-500 ml-12  cursor-text font-mono pointer-events-none"
            x-text="monacoPlaceholderText"
        ></div>
    </div>
</div>
