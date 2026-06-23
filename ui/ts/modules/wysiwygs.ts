import OverType from 'overtype';

export default function initWysiwygs() {
    const editor = document.querySelector('[data-wysiwyg-editor]');

    if (!editor) return;

    const editors = OverType.init('[data-wysiwyg-editor]', {
        toolbar: true,
        value: '',
        theme: {
            name: 'my-theme',
            colors: {
                bgPrimary: 'var(--background)',
                bgSecondary: 'var(--background)',
                text: 'var(--foreground)',
                h1: 'var(--foreground)',
                h2: 'var(--foreground)',
                h3: 'var(--foreground)',
                strong: 'var(--foreground)',
                em: 'var(--foreground)',
                link: '#4169e1',
                code: 'var(--background)',
                codeBg: 'var(--accent-foreground)',
                blockquote: 'var(--foreground)',
                hr: 'var(--foreground)',
                syntaxMarker: 'var(--foreground)',
                cursor: 'var(--foreground)',
                selection: 'var(--accent-foreground)',
            },
        },
        onChange: (value, instance) => {
            const input = instance.element
                .previousElementSibling as HTMLInputElement | null;

            if (input && value !== '') {
                input.value = value;
            }
        },
    });

    editors.forEach((editor) => {
        const input = editor.element
            .previousElementSibling as HTMLInputElement | null;

        if (input) {
            editor.setValue(input.value);
        }
    });
}
