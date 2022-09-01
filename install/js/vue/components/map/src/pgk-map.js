/**
 *  Если написать что-то не на ANSII (EN), то  автоматически будет выбрана кодировка UTF-8
 *  Это происходит автоматически. Больше ничего делать не нужно.
 *  Потому что это битрикс =)
 */

import {BitrixVue} from 'ui.vue';

import {Vuex} from 'ui.vue.vuex';

import PrimeVue from 'primevue/config/PrimeVue.js';
import ToastService from 'primevue/toastservice/ToastService.js';

import 'primevue/resources/themes/saga-blue/theme.css'
import 'primevue/resources/primevue.min.css'
import 'primeicons/primeicons.css'

import Application from './components/Application.vue';

import API from './base/api.js';
import './base/complete.js';

import 'svg.js/dist/svg.min';
import 'svg.draw.js/dist/svg.draw.min';
import 'svg.draggable.js/dist/svg.draggable.min'

BitrixVue.use(PrimeVue);
BitrixVue.use(ToastService);

API();

document.addEventListener("DOMContentLoaded", () => {
    BX.Vue.Map.run();
});

BX.Vue.Map.run = () => {
    const store = Vuex.createStore({
        state()
        {
            return {
                showIconAdd: false,
                deleteIconMod: false,
                loading: false,
                currentMap: BX.Vue.Map.Component.map.ID,
                clickIcon: null,
                breadcrumbs: {
                    home: {
                        label: BX.Vue.Map.Component.map.NAME,
                        disabled: true,
                        mapId: BX.Vue.Map.Component.map.ID,
                        command:() => {
                            BX.Vue.Map.App.$store.commit('currentMap', BX.Vue.Map.Component.map.ID);
                        }
                    },
                    items: [],
                }
            }
        },

        getters: {
            showIconAdd: function showIconAdd(state) {
                return state.showIconAdd;
            },
            loading: function loading(state) {
                return state.loading;
            },
            deleteIconMod: (state) => {
                return state.deleteIconMod;
            },
            currentMap: (state) => {
                return state.currentMap;
            },
            clickIcon: (state) => {
                return state.clickIcon;
            },
            breadcrumbs: (state) => {
                return state.breadcrumbs;
            },

        },

        mutations: {
            showIconAdd: function (state, payload) {
                state.showIconAdd = {
                    type: payload
                }
            },
            loading: function (state) {
                state.loading = !state.loading;
            },
            deleteIconMod: (state) => {
                state.deleteIconMod = !state.deleteIconMod;
            },
            currentMap: (state, payload) => {
                state.currentMap = payload;
            },
            clickIcon: (state, payload) => {
                state.clickIcon = payload;
            },
            breadcrumbsAdd: (state, payload) => {
                const items = [state.breadcrumbs.home].concat(state.breadcrumbs.items);
                state.breadcrumbs.items.forEach(item => item.disabled = false);
                const find = items.findIndex((item) => item.mapId === payload.mapId);
                switch (find) {
                    case -1: state.breadcrumbs.items.push(payload); break;
                    case 0: state.breadcrumbs.items = []; break;
                    default: {
                        state.breadcrumbs.items = state.breadcrumbs.items.filter((item, i) => i < find);
                        state.breadcrumbs.items[state.breadcrumbs.items.length-1].disabled = true;
                    }
                }
                state.breadcrumbs.home.disabled = !state.breadcrumbs.items.length;
            },
            breadcrumbsRemove: (state, payload) => {
                state.clickIcon = payload;
            },
        }
    });

    BX.Vue.Map.App = BitrixVue.createApp({
        store,
        components: {
            Application
        },
        template: `
        <Application/>
    `
    });
    BX.Vue.Map.App.mount('#pgk-map');

    BX.Vue.Map.showIconAdd = (type) => {
        BX.Vue.Map.App.$store.commit('showIconAdd', type);
    }
}
