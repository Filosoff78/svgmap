import Object from "./object.js";
import tippy from "tippy.js";

export default class OSquare extends Object {
    constructor() {
        super();
    }

    create() {
        const item = BX.Vue.Map.Canvas.polygon().fill({ color: 'grey', opacity: 0.4 }).stroke({ width: 1 }).draw();

        item.on('drawstart', function(e) {
            document.addEventListener('keydown', function(e){
                if(e.keyCode == 13){
                    item.draw('done');
                    item.off('drawstart');
                    item.draggable();
                }
            });
        });

        item.on('drawstop', function(){
            //document.removeEventListener('keydown')
        });

        this._item = item;
    }

    save() {
        const data = JSON.stringify({
            type: 'square',
            cordX: this._item.x(),
            cordY: this._item.y(),
            points: this._item.node.getAttribute('points'),
        });
        return {
            DATA: data,
            MAP: this._map,
            NAME: this._name,
        };
    }

    load(object) {
        this._item = BX.Vue.Map.Canvas.polygon(object.DATA.points).fill({ color: 'grey', opacity: 0.4 }).stroke({ width: 1 })
        this._name = object.NAME;
        this._mapTo = object.MAP_TO;
        this._id = object.ID;
        super.createAfter();
    }
}
