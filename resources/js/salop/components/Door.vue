<template>
  <div class="container">
    <a href="#" @click="openDoor" class="btn btn-primary">Abrir Porta</a>
    <div class="embed-responsive embed-responsive-16by9">
      <iframe class="embed-responsive-item" :src="iframe"></iframe>
    </div>
  </div>
</template>

<script>
export default {
  beforeRouteEnter: (to, from, next) => {
    axios
      .get(
        location.protocol +
          "//" +
          process.env.MIX_AUTH_API +
          "/user/permissions/accessSALOP"
      )
      .then((response) => {
        if (response.data === 1) {
          next();
        } else {
          next({ path: "/unauthorized" });
        }
      });
  },
  data() {
    return {
      iframe: process.env.MIX_GDS3710_VIDEO,
    };
  },
  methods: {
    openDoor() {
      axios
        .get(
          location.protocol +
            "//" +
            process.env.MIX_SALOP_API +
            "/door/open"
        )
        .then((response) => {
          toastr.success("Porta Aberta", "Video Porteiro");
        });
    },
  },
};
</script>