
import ko from 'knockout';

export default class Application {
    static boot() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.#init());
        } else {
            this.#init();
        }
    }

    static #init(): void {
        window.ko || (window.ko = Application.#bootKnockout());
    }

    /**
     * @returns {*}
     */
    static #bootKnockout() {
        const nodes = document.querySelectorAll('[data-vm]');

        for (let node of nodes) {
            let vm = require(`./view-model/${node.getAttribute('data-vm')}.js`).default;

            ko.applyBindings(new vm(node), node);
        }

        return ko;
    }
}
