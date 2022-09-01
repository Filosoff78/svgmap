<template>
  <g v-bind:class="classObject"
     :transform="'matrix(1,0,0,1,'+icon.COORDX+','+icon.COORDY+')'"
     ref="icon"
     :data-icon-id="icon.ID"
     @click="onClickIcon(icon.ID)"
  >
    <path class="fil3" d="M0 0c-9.67,0 -17.5,7.84 -17.5,17.5 0,2.05 0.55,4.88 1.33,6.7l16.17 37.5 16.23 -37.68c0.71,-1.64 1.26,-4.47 1.26,-6.52 0,-9.66 -7.83,-17.5 -17.49,-17.5z"/>
    <path class="fil4" d="M0 0l0 0 0 61.7 16.23 -37.68c0.71,-1.64 1.26,-4.47 1.26,-6.52 0,-9.66 -7.83,-17.5 -17.49,-17.5z"/>
    <path class="fil5" d="M0 27c6.27,0 11.38,-5.11 11.38,-11.38 0,-6.27 -5.11,-11.39 -11.38,-11.39 -6.27,0 -11.39,5.12 -11.39,11.39 0,6.27 5.12,11.38 11.39,11.38z"/>
    <ConfirmPopup></ConfirmPopup>
  </g>
</template>

<script>
import tippy from "tippy.js";
import 'tippy.js/dist/tippy.css';
import 'tippy.js/animations/scale.css';
import 'tippy.js/themes/light.css';
import { MessageBox } from 'ui.dialogs.messagebox';

const deleteButton = document.querySelector('button.button__delete');

export default {
  name: "icon",

  props: {
    icon: Object,
  },

  mounted() {
    if (!this.icon.GRAB) {
      tippy(this.$refs.icon, {
        content: this.icon.NAME,
        animation: 'fade',
        theme: 'light',
      });
    }
  },

  computed: {
    classObject: function () {
      return {'grab' : this.icon.GRAB}
    },
    deleteIconMod: function () {
      return this.$store.getters.deleteIconMod;
    },
    icons: function () {
      return this.$store.getters.icons;
    },
  },

  methods: {
    onClickIcon: function (iconId) {
      if (this.deleteIconMod) {
        const index = this.icons.findIndex(icon => icon.ID === iconId);
        if (index === -1) return false;
        const messageBox = BX.UI.Dialogs.MessageBox.create(
            {
              message: "Вы действительно хотите удалить иконку "+this.icons[index].NAME+"?",
              title: "Подтверждение удаления",
              buttons: BX.UI.Dialogs.MessageBoxButtons.OK_CANCEL,
              onOk: (messageBox) =>
              {
                this.$store.commit('loading');
                BX.Vue.Map.API.deleteIcon(this.icons[index].ID)
                    .then(response => {
                      this.$store.commit('loading');
                      this.$store.commit('deleteIcon', this.icons[index].ID);
                    })
                    .catch(error => {
                      console.log(error);
                    });
                messageBox.close();
              },
            }
        );
        messageBox.show();
      }
    }
  },

  watch: {
    deleteIconMod(newCount) {
      if (newCount) {
        deleteButton.classList.add('active');
      } else {
        deleteButton.classList.remove('active');
      }
    }
  },
}
</script>

<style>
g:hover .fil2 {fill:#505c68}
.fil3 {fill:#A62438}
.grab > .fil3 {fill:#006400}
g:hover .fil3 {fill:#6eb7e0}
.fil4 {fill:#DF314A}
.grab > .fil4 {fill:#228B22}
g:hover .fil4 {fill:#b3dff3}
.fil5 {fill:white}
</style>


<style>
button.ui-btn.ui-btn-primary.ui-btn-icon-remove.button__delete.active {
  --ui-btn-background: #3bc8f5;
  --ui-btn-background-hover: #3eddff;
  --ui-btn-background-active: #12b1e3;
  --ui-btn-border-color: #3bc8f5;
  --ui-btn-border-color-hover: #3eddff;
  --ui-btn-border-color-active: #12b1e3;
  --ui-btn-color: #fff;
  --ui-btn-color-hover: #fff;
  --ui-btn-color-active: #fff;
}
</style>
