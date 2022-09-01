<template>
  <div id="polygon"></div>
</template>

<script>
export default {
  name: "DrawPolygon",

  computed: {
    map() {
      return this.$store.getters.map;
    },
  },

  mounted() {

  },
};
</script>

<style scoped>
#polygon {
  position: absolute;
}
</style>
