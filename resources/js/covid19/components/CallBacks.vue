<template>
<div>
    <div v-if="loading" class="loading">Loading...</div>
    <h3 v-if="user">
        CallBacks
        <span>{{user.name}}</span>
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
