import OIcon from "./oicon";
import OSquare from "./osquare";

export const getObjectClass = (type) => {
    let object = null;
    switch (type) {
        case 'icon': object = new OIcon(); break;
        case 'square': object = new OSquare(); break;
    }
    return object;
};

export const loadObjects = (objects) => {
    objects.forEach(object => {
        object.DATA = JSON.parse(object.DATA);
        getObjectClass(object.DATA.type).load(object);
    })
}
