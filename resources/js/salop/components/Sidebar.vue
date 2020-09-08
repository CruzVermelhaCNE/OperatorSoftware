<template>
  <nav class="col-md-2 d-none d-md-block bg-dark text-light sidebar">
    <div class="sidebar-sticky">
      <div v-bind:key="section.id" v-for="section in sections">
        <SidebarSection v-bind:section="section"/>
      </div>
    </div>
  </nav>
</template>

<script>
import SidebarSection from './SidebarSection'
export default {
  name: "Sidebar",
  components: {
    SidebarSection,
  },
  props: ["sections"],
};
</script>