export default function ()
{
    BX.Vue.Map.API = Object.create(null);

    BX.Vue.Map.API.addIcon = (icon) => new Promise((resolve, reject) => {
        const options = {
            mode: 'class',
            data: { icon },
        };

        BX.ajax.runComponentAction('pgk:map', 'addIcon', options)
            .then(resolve)
            .catch(reject);
    });

    BX.Vue.Map.API.deleteIcon = (id) => new Promise((resolve, reject) => {
        const options = {
            mode: 'class',
            data: { id },
        };

        BX.ajax.runComponentAction('pgk:map', 'deleteIcon', options)
            .then(resolve)
            .catch(reject);
    });

    BX.Vue.Map.API.loadMap = (id) => new Promise((resolve, reject) => {
        const options = {
            mode: 'class',
            data: { id },
        };

        BX.ajax.runComponentAction('pgk:map', 'getMap', options)
            .then(resolve)
            .catch(reject);
    });
}
