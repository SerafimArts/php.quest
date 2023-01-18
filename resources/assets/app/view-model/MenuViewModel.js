
import ko from 'knockout';
import Menu from "../model/Menu";
import Category from "../model/Category";
import DocumentationViewModel from "./DocumentationViewModel";

export default class MenuViewModel {
    categories: KnockoutObservableArray<Category> = ko.observableArray([]);
    loading: Boolean = false;

    constructor(ctx: HTMLElement, page: DocumentationViewModel) {
        this.page = page;
        this.#load(ctx);
    }

    #load(ctx: HTMLElement) {
        for (let categoryNode: HTMLElement of ctx.querySelectorAll('[data-id=category]')) {
            let title = categoryNode.querySelector('[data-id=title]');
            let category = new Category(title.textContent.trim());

            for (let menuNode: HTMLElement of categoryNode.querySelectorAll('[data-link]')) {
                category.menu.push(Menu.fromNode(menuNode));
            }

            this.categories.push(category);
        }
    }

    async select(menu: Menu) {
        if (this.loading) {
            return;
        }

        this.#unselectAll();

        this.loading = true;
        menu.loading(true);

        try {
            let response = await this.load(menu);

            window.history.pushState(response, response.title, `${response.url}`);
            document.title = `${response.title} — PHP::Quest`;

            this.page.current(response);

            menu.active(true);
        } finally {
            this.loading = false;
            menu.loading(false);
        }
    }

    async load(menu: Menu): Object {
        if (sessionStorage[menu.id]) {
            return JSON.parse(sessionStorage.getItem(menu.id));
        }

        let response: Response = await fetch('/graphql', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                operationName: null,
                query: `query($id: ID!) {
                    page: pageById(id: $id) {
                        id
                        url
                        title
                        content
                        isAvailable
                        updatedAt
                        category {
                            title
                        }
                    }
                }`,
                variables: {
                    id: menu.id,
                }
            }),
        });

        let json: Object = await response.json();

        sessionStorage.setItem(menu.id, JSON.stringify(json.data.page));

        return json.data.page;
    }

    #unselectAll(): void {
        for (let category of this.categories()) {
            for (let menu of category.menu()) {
                menu.active(false);
            }
        }
    }
}
