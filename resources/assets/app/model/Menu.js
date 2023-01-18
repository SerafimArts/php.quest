
import ko from 'knockout';

export default class Menu {
    active: KnockoutObservable<boolean> = ko.observable(false);
    loading: KnockoutObservable<boolean> = ko.observable(false);

    constructor(id: String, title: String, active: Boolean) {
        this.id = id;
        this.title = title;
        this.active(active);
    }

    static fromNode(node: HTMLElement): Menu {
        return new Menu(
            node.getAttribute('data-link'),
            node.textContent.trim(),
            node.classList.contains('active'),
        );
    }
}
