
import ko from 'knockout';

export default class Navigation {
    active: KnockoutObservable<boolean> = ko.observable(false);

    constructor(title: String, slug: String, node: HTMLElement) {
        this.title = title;
        this.slug = slug;
        this.node = node;
    }
}
