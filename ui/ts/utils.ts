export function gid<T extends HTMLElement>(id: string): T | null {
    const element = document.getElementById(id);
    if (!element) {
        console.error(`Element with id '${id}' not found`);
        return null;
    }
    return element as T;
}

export function qsa<T extends Element>(
    selector: string,
    parent: Element | Document = document
): NodeListOf<T> {
    const elements = parent.querySelectorAll<T>(selector);
    if (!elements) {
        console.error(`Could not find elements with '${selector}' selector`);
    }
    return elements;
}

export function qs<T extends Element>(
    selector: string,
    parent: Element | Document = document
): T | null {
    const element = parent.querySelector<T>(selector);
    if (!element) {
        console.error(`Could not find an element with '${selector}' selector`);
        return null;
    }
    return element;
}

export function setCookie(cname: string, cvalue: string, exdays = 30) {
    const d = new Date();
    d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
    let expires = 'expires=' + d.toUTCString();
    document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/';
}

export function getCookie(cname: string) {
    let name = cname + '=';
    let ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return '';
}
