import Object from "./object.js";
import tippy from "tippy.js";

export default class OIcon extends Object {
    constructor() {
        super();
    }

    create() {
        const item = BX.Vue.Map.Canvas.nested()
        item.path('M0 0c-9.67,0 -17.5,7.84 -17.5,17.5 0,2.05 0.55,4.88 1.33,6.7l16.17 37.5 16.23 -37.68c0.71,-1.64 1.26,-4.47 1.26,-6.52 0,-9.66 -7.83,-17.5 -17.49,-17.5z').fill('#A62438');
        item.path('M0 0l0 0 0 61.7 16.23 -37.68c0.71,-1.64 1.26,-4.47 1.26,-6.52 0,-9.66 -7.83,-17.5 -17.49,-17.5z').fill('#DF314A');
        item.path('M0 27c6.27,0 11.38,-5.11 11.38,-11.38 0,-6.27 -5.11,-11.39 -11.38,-11.39 -6.27,0 -11.39,5.12 -11.39,11.39 0,6.27 5.12,11.38 11.39,11.38z').fill('#FFFFFF');
        item.move(
            BX.Vue.Map.App.$store.getters.currentMap.WIDTH / 2,
            BX.Vue.Map.App.$store.getters.currentMap.HEIGHT / 2
        );
        item.draggable();
        this._item = item;
    }

    save() {
        const data = JSON.stringify({
            type: 'icon',
            cordX: this._item.x(),
            cordY: this._item.y(),
        });
        return {
            DATA: data,
            MAP: this._map,
            NAME: this._name,
        };
    }

    load(object) {
        this.create();
        this._item.move(object.DATA.cordX, object.DATA.cordY);
        this._name = object.NAME;
        this._mapTo = object.MAP_TO;
        this._id = object.ID;
        this._item.draggable(false);
        super.createAfter();
    }
}
