<template>
  <div>
    <h6 v-if="section.header !== null"
      class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1"
    >
      <span v-html="section.header"></span>
    </h6>
    <ul class="nav flex-column mb-2">
      <div v-bind:key="item.id" v-for="item in section.items">
        <SidebarItem v-bind:item="item" />
      </div>
    </ul>
  </div>
</template>

<script>
import SidebarItem from './SidebarItem'
export default {
  name: "SidebarSection",
  components: {
    SidebarItem,
  },
  props: ["section"],
};
</script>