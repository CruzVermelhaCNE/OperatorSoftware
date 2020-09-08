<template>
  <div>
    <div v-if="loading === true">Loading...</div>
    <div v-if="extensions !== null && iframe === null">
      <h3>Selecione a sua Extensão</h3>
      <select class="form-control" @change="extensionPicked">
        <option></option>
        <option
          :key="extension.number"
          v-for="extension in extensions"
          v-bind:value="extension.number"
        >{{extension.number}}</option>
      </select>
    </div>
    <iframe v-if="iframe" :src="iframe"></iframe>
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
      loading: null,
      extensions: null,
      iframe: null,
    };
  },
  activated() {
    this.fetchData();
  },
  methods: {
    fetchData() {
      this.loading = true;
      axios
        .get(
          location.protocol +
            "//" +
            process.env.MIX_AUTH_API +
            "/user/extensions"
        )
        .then((response) => {
          this.extensions = response.data;
          this.loading = false;
        });
    },
    extensionPicked(event) {
      const number = event.target.value;
      let password = this.extensions.filter(
        (entry) => entry.number == number
      )[0].password;
      this.iframe =
        process.env.MIX_FOP2_ADDRESS + "?exten=" + number + "&pass=" + password;
    },
  },
};
</script>


<style scoped>
div {
  height: 100%;
}
iframe {
  display: block;
  width: 100%;
  height: 100%;
  border: none;
}
</style>