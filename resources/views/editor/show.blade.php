<x-layout>
    <div class="editor-container mt-4">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center">
                <input type="text" id="filename" class="bg-gray-800 text-white px-3 py-2 rounded border border-gray-700 focus:outline-none focus:border-blue-500" value="untitled.md" placeholder="Filename">
            </div>
        </div>

        <div class="flex bg-gray-850 rounded-lg overflow-y-scroll border border-gray-700">
            <!-- Line numbers -->
            <div id="line-numbers" class="py-2 px-2 bg-gray-800 text-gray-500 text-right select-none" style="min-width: 3rem;">
                1
            </div>

            <!-- Editor area -->
            <textarea id="editor" class="flex-1 bg-gray-900 text-gray-300 p-2 focus:outline-none font-mono" style="resize: none; min-height: 400px; tab-size: 4;"></textarea>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editor = document.getElementById('editor');
            const lineNumbers = document.getElementById('line-numbers');
            const filename = document.getElementById('filename');

            // Update line numbers when text changes
            function updateLineNumbers() {
                const lines = editor.value.split('\n');
                let lineNumbersHTML = '';

                for (let i = 1; i <= lines.length; i++) {
                    lineNumbersHTML += i + '<br>';
                }

                lineNumbers.innerHTML = lineNumbersHTML;
            }

            // Initialize with one line
            updateLineNumbers();

            // Update line numbers when text changes
            editor.addEventListener('input', updateLineNumbers);

            // Handle tab key
            editor.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    e.preventDefault();

                    // Insert tab at cursor position
                    const start = this.selectionStart;
                    const end = this.selectionEnd;

                    this.value = this.value.substring(0, start) + '    ' + this.value.substring(end);

                    // Move cursor after the inserted tab
                    this.selectionStart = this.selectionEnd = start + 4;

                    // Update line numbers
                    updateLineNumbers();
                }
            });

            // Apply syntax highlighting for markdown
            function applySyntaxHighlighting() {
                // For a complete solution, a library like CodeMirror or Monaco Editor would be better
                // But for this simple implementation, we'll use a pre-styled textarea

                // Add a class to indicate this is a markdown editor
                editor.classList.add('markdown-editor');

                // Set up some basic styling
                editor.style.color = '#d1d5db'; // Default text color
            }

            // Apply initial syntax highlighting
            applySyntaxHighlighting();

            // Add a placeholder with markdown example
            editor.placeholder = `# Adam's Easter Egg
Well, isn't this a fun one? Feel free to save through File > Save. Contents will be wiped on page reload.

## Heading 2

**Bold text** and *italic text*

- List item 1
- List item 2

[Link example](https://example.com)

\`\`\`
Code block example
\`\`\`

> Blockquote example
`;

            // Handle save functionality
            function saveFile() {
                const content = editor.value;
                const filenameValue = filename.value || 'untitled.md';

                // Create a form to submit the content
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("editor.save") }}';
                form.style.display = 'none';

                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // Add content
                const contentInput = document.createElement('input');
                contentInput.type = 'hidden';
                contentInput.name = 'content';
                contentInput.value = content;
                form.appendChild(contentInput);

                // Add filename
                const filenameInput = document.createElement('input');
                filenameInput.type = 'hidden';
                filenameInput.name = 'filename';
                filenameInput.value = filenameValue;
                form.appendChild(filenameInput);

                // Submit the form
                document.body.appendChild(form);
                form.submit();
            }

            // Listen for save command (Ctrl+S or Command+S)
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                    e.preventDefault();
                    saveFile();
                }
            });

            // Expose saveFile function to window for menu click handling
            window.saveFile = saveFile;
        });
    </script>

    <style>
        #editor {
            font-family: 'Courier New', monospace;
            line-height: 1.5;
            white-space: pre;
            overflow-wrap: normal;
            overflow-x: auto;
            padding: 10px;
            color: #d1d5db;
            caret-color: #fff;
        }

        #editor::placeholder {
            color: #6b7280;
            opacity: 0.7;
        }

        #line-numbers {
            font-family: 'Courier New', monospace;
            line-height: 1.5;
            user-select: none;
            padding-top: 10px;
            color: #6b7280;
            background-color: #1f2937;
            border-right: 1px solid #374151;
            text-align: right;
            padding-right: 8px;
        }

        .editor-container {
            height: calc(100vh - 250px);
            display: flex;
            flex-direction: column;
        }

        .editor-container .flex.bg-gray-850 {
            flex: 1;
            display: flex;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        #editor {
            height: 100%;
            min-height: unset;
            background-color: #111827;
        }

        /* Filename input styling */
        #filename {
            background-color: #1f2937;
            color: #d1d5db;
            border-color: #374151;
            border-radius: 4px;
            padding: 8px 12px;
            font-family: 'Courier New', monospace;
            width: 300px;
        }

        #filename:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
        }

        /* Make the editor look more like a code editor */
        .markdown-editor {
            tab-size: 4;
            -moz-tab-size: 4;
        }
    </style>
</x-layout>
