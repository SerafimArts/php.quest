
import ko from 'knockout';

export default class Navigation {
    active: KnockoutObservable<boolean> = ko.observable(false);

    constructor(title: String, anchor: String, node: HTMLElement) {
        this.title = title;
        this.anchor = `#${anchor}`;
        this.node = node;
    }

    static fromNode(node: HTMLElement): Navigation {
        return new Navigation(
            node.textContent.trim(),
            node.getAttribute('data-anchor'),
            node,
        );
    }
}
