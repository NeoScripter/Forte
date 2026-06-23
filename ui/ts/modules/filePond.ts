import { defineFilePond } from 'filepond';
import { locale } from 'filepond/locales/en-gb';

export default function initFilePond() {
    const instances = defineFilePond({
        locale,
    });

    for (const instance of instances) {
        instance.onchange = () => {
            const files = instance.currentEntries;

            const imageWrapper = instance.nextElementSibling;

            if (!imageWrapper) return;

            const template =
                imageWrapper.nextElementSibling as HTMLTemplateElement | null;

            if (!template) return;

            imageWrapper.innerHTML = '';

            for (const file of files) {
                const clone = document.importNode(template.content, true);
                const imgTag = clone.querySelector('img');

                if (!imgTag) continue;

                imgTag.src = URL.createObjectURL(file.src);

                imageWrapper.appendChild(clone);

                console.log(file);
            }
        };
    }
}
