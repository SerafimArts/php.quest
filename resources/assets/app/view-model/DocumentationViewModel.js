
import ko from 'knockout';
import Navigation from '../model/Navigation';

export default class DocumentationViewModel {
    anchors: KnockoutObservableArray<Navigation> = ko.observableArray([]);

    constructor(ctx: HTMLElement) {
        this.#load(ctx);

        this.#updateAsideNavigation(window.scrollY);
        document.addEventListener("scroll", () => {
            window.requestAnimationFrame(() => {
                this.#updateAsideNavigation(window.scrollY);
            });
        });
    }

    #updateAsideNavigation(position: Number): void {
        let minimal: Number = Number.MAX_VALUE;
        let current: ?Navigation = null;

        for (let nav of this.anchors()) {
            let diff = position - nav.node.offsetTop;

            if (diff > -40 && minimal > diff) {
                minimal = diff;
                current = nav;
            }
        }

        if (current !== null) {
            for (let nav of this.anchors()) {
                if (nav !== current) {
                    nav.active(false);
                }
            }
            current.active(true);
        }
    }

    #load(node: HTMLElement): void {
        for (let anchor of node.querySelectorAll('[data-anchor]')) {
            let navigation = new Navigation(
                anchor.textContent.trim(),
                anchor.getAttribute('data-anchor'),
                anchor,
            );

            this.anchors.push(navigation);
        }
    }
}
