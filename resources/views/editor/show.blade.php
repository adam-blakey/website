<x-layout>
    @push('head')
        @vite('resources/js/editor.js')
    @endpush

    <div class="editor-container mt-4">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center">
                <input type="text" id="filename" class="bg-gray-800 text-white px-3 py-2 rounded border border-gray-700 focus:outline-none focus:border-blue-500" value="untitled.md" placeholder="Filename">
            </div>
        </div>

        <div id="editor" class="flex-1 min-h-0 rounded-lg overflow-hidden border border-gray-700"></div>
    </div>

    <style>
        .editor-container {
            height: calc(100vh - 250px);
            display: flex;
            flex-direction: column;
        }

        #filename {
            background-color: var(--color-gray-950);
            color: #d1d5db;
            border-color: var(--color-gray-700);
            border-radius: 4px;
            padding: 8px 12px;
            font-family: var(--font-mono);
            width: 300px;
        }

        #filename:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
        }
    </style>
</x-layout>
