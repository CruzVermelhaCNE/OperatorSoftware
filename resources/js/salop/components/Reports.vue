<template>
  <div>
    <h1 class="text-center">Relatórios</h1>
    <h5 class="text-center">Por Implementar</h5>
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
          "/user/permissions/isManager"
      )
      .then((response) => {
        if (response.data === 1) {
          next();
        } else {
          next({ path: "/unauthorized" });
        }
      });
  },
};
</script>