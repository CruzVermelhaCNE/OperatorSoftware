<template>
  <li class="nav-item" :class="li_class">
    <div v-if="item.route !== null">
      <router-link class="nav-link" :to="item.route"><span :data-feather="item.icon"></span>
        {{item.name}}</router-link>
    </div>
    <div v-else>
      <a class="nav-link" :href="item.external_route">
        <span :data-feather="item.icon"></span>
        {{item.name}}
      </a>
    </div>
  </li>
</template>

<script>
export default {
  name: "SidebarItem",
  props: ["item"],
  computed: {
    li_class() {
      return this.item.bottom === true ? "bottom" : "";
    },
  },
};
</script>

<style scoped>
.bottom {
  position: absolute;
  bottom: 0;
}
</style>