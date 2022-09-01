<template>
  <div id="app">
    <section
        ref="el"
        :style="{
        cursor: isDragging ? 'grabbing' : 'grab',
        scrollSnapType: isDragging ? '' : '',
      }"
        @mousedown="onMouseDown"
        @mouseup="onMouseUp"
    >
      <Map/>
    </section>
    <div class="scale" style="display:none">
      <div>
        <button class="ui-btn" @click="scaleFunc(0)" :disabled="this.scale >= 1.8">+</button>
      </div>
      <div class="pt-2">
        <button class="ui-btn" @click="scaleFunc(1)" :disabled="this.scale <= 0.2">-</button>
      </div>
    </div>
  </div>
</template>

<script>
import Map from './Map.vue';
import AddIcon from './AddIcon.vue';

export default {
  name: "App",
  components:{Map, AddIcon},
  data() {
    return {
      isDragging: false,
      cursorPos: [0, 0],
      el: null,
      scale: 1.0,
    };
  },
  mounted() {
    window.addEventListener("mouseup", this.onMouseUp);
  },
  destroyed() {
    window.removeEventListener("mouseup", this.onMouseUp);
  },
  methods: {
    scaleFunc(type) {
      type ? this.scale -= 0.1 : this.scale += 0.1;
      document.getElementById('map').style.transform = `scale(${this.scale})`;
    },

    onMouseDown(ev) {
      this.cursorPos = [ev.pageX, ev.pageY];
      this.isDragging = true;

      window.addEventListener("mousemove", this.onMouseHold);
    },

    onMouseUp(ev) {
      window.removeEventListener("mousemove", this.onMouseHold);
      this.isDragging = false;
    },

    onMouseHold(ev) {
      ev.preventDefault();

      requestAnimationFrame(() => {
        const delta = [
          ev.pageX - this.cursorPos[0],
          ev.pageY - this.cursorPos[1],
        ];

        this.cursorPos = [ev.pageX, ev.pageY];

        if (!this.$refs.el) return;
        this.$refs.el.scrollBy({
          left: -delta[0],
          top: -delta[1],
        });
      });
    },
  },
};
</script>

<style scoped>
#app {
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  width: 100%;
}
section {
  overflow-y: hidden !important;
  overflow-x: hidden !important;
  width: 100%;
  height: 400px;
  display: flex;
}
.scale {
  position: absolute;
  top: 1em;
  left: 1em;
  background-color: white;
  padding: 5px 5px;
  border: 2px solid #818b9a;
  border-radius: 5px;
}
.scale > div > button {
  width: 2em;
}
</style>
