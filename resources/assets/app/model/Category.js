
import ko from 'knockout';
import Menu from "./Menu";

export default class Category {
    menu: KnockoutObservableArray<Menu> = ko.observableArray([]);

    constructor(title: String) {
        this.title = title;
    }
}
