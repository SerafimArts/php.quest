
import DocumentationViewModel from "./DocumentationViewModel";
import MenuViewModel from "./MenuViewModel";

export default class LayoutViewModel {
    constructor(ctx: HTMLElement) {
        this.page = new DocumentationViewModel(
            ctx.querySelector('[data-id=page]')
        );

        this.menu = new MenuViewModel(
            ctx.querySelector('[data-id=menu]'),
            this.page,
        );
    }
}
