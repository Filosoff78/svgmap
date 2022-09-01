<template>
  <div class="map pt-2">
    <AddIcon/>
    <div id="map">
      <DrawPolygon/>
    </div>
  </div>
</template>

<script>
import Icon from "./Icon.vue";
import AddIcon from './AddIcon.vue';
import DrawPolygon from "./DrawPolygon";
import { getObjectClass, loadObjects } from "../base/objecthelpers";

export default {
  name: "Map",

  components: {
    DrawPolygon,
    Icon, AddIcon
  },

  data() {
    return {
      bool: false,
      iconsMap: null,
    }
  },

  mounted() {
    this.loadMap();
  },

  computed: {
    currentMap() {
      return this.$store.getters.currentMap;
    },
    showIconAdd: function() {
      return this.$store.getters.showIconAdd;
    },
  },

  methods: {
    loadMap: function () {
      BX.Vue.Map.API.loadMap(this.currentMap)
          .then(response => {
            this.$store.commit('currentMap', response.data);
            if (BX.Vue.Map.Canvas) BX.Vue.Map.Canvas.remove();
            BX.Vue.Map.Canvas = SVG('polygon').size(this.currentMap.WIDTH, this.currentMap.HEIGHT);
            BX.Vue.Map.Canvas.image(this.currentMap.SRC, this.currentMap.WIDTH, this.currentMap.HEIGHT);
            loadObjects(this.currentMap.ICONS);
            const app = this;
            document.querySelectorAll('[data-svgObject]').forEach(icon => {
              icon.addEventListener('click', () => app.clickIcon(icon));
            });
            const mapId = Number(this.currentMap.ID_BD);
            this.$store.commit('breadcrumbsAdd', {
              label: this.currentMap.NAME,
              mapId: mapId,
              disabled: true,
              command:() => {
                BX.Vue.Map.App.$store.commit('currentMap', mapId)
              }
            });
          })
          .catch(error => {
            console.log(error);
          });
    },
    clickIcon: function (icon) {
      if(icon.dataset.mapTo > 0) {
        const messageBox = BX.UI.Dialogs.MessageBox.create(
            {
              message: `Вы хотите перейти к ${icon.dataset.name}?`,
              title: "Карта",
              buttons: BX.UI.Dialogs.MessageBoxButtons.OK_CANCEL,
              onOk: (messageBox) =>
              {
                this.$store.commit('currentMap', icon.dataset.mapTo);
                messageBox.close();
              },
            }
        );
        messageBox.show();
      }
    }
  },

  watch: {
    showIconAdd(newCount) {
      if (newCount) {
        BX.Vue.Map.Canvas.currentItem = getObjectClass(newCount.type);
        BX.Vue.Map.Canvas.currentItem.create();
      }
    },
    currentMap(newCount) {
      if (!newCount.SRC) this.loadMap();
    },
  },

}
</script>

<style>
#map {
  position: relative;
  transition: 1s;
}

#map > svg {
  position: absolute;
}

/*.fil3 {fill:#A62438}*/
svg:hover > path.fil3 {fill:#6eb7e0}
/*.fil4 {fill:#DF314A}*/
svg:hover > path.fil4 {fill:#b3dff3}
/*.fil5 {fill:white}*/
polygon:hover {
  fill:white;
}
</style>
