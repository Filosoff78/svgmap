<template>
  <div class="rel">
    <Toast position="top-right" />
    <Breadcrumb :home="breadcrumbs.home" :model="breadcrumbs.items" />
    <BlockUI :blocked="loading">
      <Scroll/>
    </BlockUI>
    <hollow-dots-spinner class="loader" v-show="loading"
        :animation-duration="1000"
        :dot-size="15"
        :dots-num="3"
        :color="'#c60440'"
    />
  </div>
</template>

<script>
import Scroll from './Scroll.vue';
import BlockUI from 'primevue/blockui';
import {HollowDotsSpinner} from 'epic-spinners'
import Toast from 'primevue/toast';
import Breadcrumb from 'primevue/breadcrumb';

export default {
  name: "application",
  components: {
    Scroll, BlockUI, HollowDotsSpinner, Toast, Breadcrumb
  },
  data() {
    return {
      value: null,
    }
  },
  computed: {
    breadcrumbs: function () {
      return this.$store.getters.breadcrumbs;
    },
    loading: function () {
      return this.$store.getters.loading;
    },
    deleteIconMod: function () {
      return this.$store.getters.deleteIconMod;
    },
    currentMap() {
      return this.$store.getters.currentMap;
    },
  },
  watch: {
    deleteIconMod(newCount) {
      if (newCount) {
        this.$toast.add({severity: 'info', summary: 'Режим удаления', detail: 'Чтобы удалить иконку кликните на нее',
          closable: false});
      } else {
        this.$toast.removeAllGroups();
      }
    }
  },
}
</script>

<style scoped>
.loader {
  position: absolute;
  left: 50%;
  top: 50%;
  -webkit-transform: translate(-50%, -50%);
  -moz-transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  -o-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
  z-index: 2001;
}
.rel {
  position: relative;
}
</style>
