<template>
<div>
    <div v-if="loading" class="loading">Loading...</div>
    <div v-if="response">
        <h3>Devolver Chamadas</h3>
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
                <tr :key="entry.id" v-for="entry in response.data">
                    <td>{{ entry.cdr_system_id }}</td>
                    <td>{{ entry.date }}</td>
                    <td>{{ entry.number }}</td>
                    <td>
                        <a href="#">Marcar como ligado</a>
                    </td>
                </tr>
            </tbody>
        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item" v-if="response.prev_page_url !== null">
                    <a class="page-link" href="#0" @click="prevPage">Anterior</a>
                </li>
                <li class="page-item"><a class="page-link" href="#0">{{response.current_page}}</a></li>
                <li class="page-item" v-if="response.next_page_url !== null">
                    <a class="page-link" href="#0" @click="nextPage">Próxima</a>
                </li>
            </ul>
            <p>Número total de páginas: {{ response.last_page }}</p>
        </nav>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            loading: null,
            response: null,
        };
    },
    created() {
        this.fetchData();
    },
    methods: {
        fetchData() {
            this.loading = true;
            axios
                .get("api/callbacks")
                .then((response) => {
                    this.response = response.data;
                    this.loading = false;
                });
        },
        prevPage() {
            this.loading = true;
            axios
                .get(this.response.prev_page_url, {
                    params: this.query
                })
                .then((response) => {
                    this.response = response.data;
                    this.loading = false;
                });
        },
        nextPage() {
            this.loading = true;
            axios
                .get(this.response.next_page_url, {
                    params: this.query
                })
                .then((response) => {
                    this.response = response.data;
                    this.loading = false;
                });
        },
    },
};
</script>
