import { EditorView, basicSetup } from 'codemirror';
import { keymap } from '@codemirror/view';
import { indentWithTab } from '@codemirror/commands';
import { markdown } from '@codemirror/lang-markdown';
import { languages } from '@codemirror/language-data';
import { oneDark } from '@codemirror/theme-one-dark';

const exampleMarkdown = `# Adam's Easter Egg
Well, isn't this a fun one? Feel free to save through File > Save. Contents will be wiped on page reload.

## Heading 2

**Bold text** and *italic text*

- List item 1
- List item 2

[Link example](https://example.com)

\`\`\`js
const codeBlock = 'example';
\`\`\`

> Blockquote example
`;

const siteColours = EditorView.theme({
    '&': { height: '100%', backgroundColor: 'var(--color-gray-900)' },
    '.cm-scroller': { fontFamily: 'var(--font-mono)', lineHeight: '1.5' },
    '.cm-gutters': {
        backgroundColor: 'var(--color-gray-950)',
        borderRight: '1px solid var(--color-gray-800)',
    },
}, { dark: true });

const editor = new EditorView({
    parent: document.getElementById('editor'),
    doc: exampleMarkdown,
    extensions: [
        basicSetup,
        keymap.of([indentWithTab]),
        markdown({ codeLanguages: languages }),
        siteColours,
        oneDark,
        EditorView.lineWrapping,
    ],
});

function saveFile() {
    const filename = document.getElementById('filename').value || 'untitled.md';
    const url = URL.createObjectURL(new Blob([editor.state.doc.toString()], { type: 'text/markdown' }));
    const link = document.createElement('a');
    link.href = url;
    link.download = filename;
    link.click();
    URL.revokeObjectURL(url);
}

document.addEventListener('keydown', (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        saveFile();
    }
});

document.querySelector('a[href="#save-file"]').addEventListener('click', (e) => {
    e.preventDefault();
    saveFile();
});

editor.focus();
