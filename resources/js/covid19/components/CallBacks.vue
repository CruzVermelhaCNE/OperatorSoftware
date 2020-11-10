<template>
<div>
    <div v-if="loading" class="loading">Loading...</div>
    <h3 v-if="user">
        CallBacks
        <table class="table table-dark">
            <thead>
                <tr>
                    <th scope="col">ID Interno</th>
                    <th scope="col">Data</th>
                    <th scope="col">Número</th>
                    <th scope="col">Acções</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                </tr>
            </tbody>
        </table>
        <span>{{ user.name }}</span>
    </h3>
</div>
</template>

<script>
export default {
    data() {
        return {
            loading: null,
            user: null,
        };
    },
    created() {
        this.fetchData();
    },
    methods: {
        fetchData() {
            this.loading = true;
            axios
                .get(location.protocol + "//" + process.env.MIX_AUTH_API + "/user/info")
                .then((response) => {
                    this.user = response.data;
                    this.loading = false;
                });
        },
    },
};
</script>
