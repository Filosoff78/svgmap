import tippy from "tippy.js";

export default class Object {
    constructor() {
        this._item = undefined;
        this._name = undefined;
        this._id = undefined;
        this._mapTo = undefined;
    }

    createAfter() {
        tippy(this._item.node, {
            content: this._name,
            animation: 'fade',
            theme: 'light',
        });
        this._item.data('id', this._id);
        this._item.data('mapTo', this._mapTo);
        this._item.data('svgObject', true);
        this._item.data('name', this._name);
    }
}
