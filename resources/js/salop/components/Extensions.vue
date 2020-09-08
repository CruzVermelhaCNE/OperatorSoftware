<template>
  <div>
    <div v-if="loading === true">Loading...</div>
    <div v-if="extensions">
      <h1 class="text-center">Extensões</h1>
      <table id="extensions" class="table table-dark" style="width:100%">
        <thead>
          <tr>
            <th>Extensão</th>
            <th>Password</th>
          </tr>
        </thead>
        <tbody>
          <tr :key="extension.id" v-for="extension in extensions">
            <td>{{ extension.number }}</td>
            <td>{{ extension.password }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  beforeRouteEnter: function (to, from, next) {
    axios
      .get(
        location.protocol +
          "//" +
          process.env.MIX_AUTH_API +
          "/user/permissions/isAdmin"
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
      loading: true,
      extensions: null,
    };
  },
  created() {
    this.fetchData();
  },
  methods: {
    fetchData() {
      axios
        .get(
          location.protocol +
            "//" +
            process.env.MIX_SALOP_API +
            "/extensions/full"
        )
        .then((response) => {
          this.extensions = response.data;
          this.loading = false;
        });
    },
  },
  watch: {
    extensions: function (val) {
      if (val === null) {
        $("#extensions_wrapper").remove();
      } else {
        Vue.nextTick(() => {
          $("#extensions").DataTable();
        });
      }
    },
  },
};
</script>