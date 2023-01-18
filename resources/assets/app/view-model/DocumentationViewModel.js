
import ko from 'knockout';
import Navigation from '../model/Navigation';

export default class DocumentationViewModel {
    current: ?KnockoutObservable<Object> = ko.observable(null);
    anchors: KnockoutObservableArray<Navigation> = ko.observableArray([]);

    constructor(ctx: HTMLElement) {
        this.#load(ctx);

        this.#updateAsideNavigation(window.scrollY);
        document.addEventListener("scroll", () => {
            window.requestAnimationFrame(() => {
                this.#updateAsideNavigation(window.scrollY);
            });
        });

        this.current.subscribe((page: ?Object) => {
            if (page === null) {
                return;
            }

            setTimeout(() => { this.#load(ctx); }, 1);
        }, null, 'change');
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
        this.anchors([]);
        for (let anchor of node.querySelectorAll('[data-anchor]')) {
            this.anchors.push(Navigation.fromNode(anchor));
        }
    }
}
