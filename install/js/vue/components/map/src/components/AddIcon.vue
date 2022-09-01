<template>
  <div v-show="showIconAdd">
    <div class="row pt-4 pb-2">
      <div class="col-8">
        <span class="p-float-label">
          <InputText id="username" type="text" v-model="name" />
          <label for="username">Название</label>
        </span>
      </div>
      <div class="col-2">
        <span class="p-float-label">
          <InputText id="idTo" type="text" v-model="idTo" />
          <label for="idTo">Путь до (ид)</label>
        </span>
      </div>
      <div class="col-2">
        <button class="ui-btn ui-btn-primary" @click="addIcon">Добавить</button>
      </div>
    </div>
  </div>
</template>

<script>
import InputText from 'primevue/inputtext';

export default {
  name: "addIcon",
  components: {InputText},
  data() {
    return {
      name: null,
      idTo: null,
    }
  },

  computed: {
    showIconAdd: function() {
      return this.$store.getters.showIconAdd;
    },
    currentMap() {
      return this.$store.getters.currentMap;
    },
  },

  methods: {
    addIcon: function () {
      this.$store.commit('loading');

      const icon = BX.Vue.Map.Canvas.currentItem.save();

      icon.NAME = this.name;
      icon.ID_TO = this.idTo;
      icon.MAP = this.currentMap.ID_BD;

      BX.Vue.Map.API.addIcon(icon)
          .then(response => {
            this.$store.commit('loading');
            this.$store.commit('showIconAdd', null);
          })
          .catch(error => {
            console.log(error);
          });
    }
  },
}
</script>

<style scoped>
.p-inputtext {
  width: 100%;
}
</style>
