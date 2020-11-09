<template>
<div>
    <div v-if="loading === true">Loading...</div>
    <div v-if="users">
        <h1 class="text-center">Utilizadores</h1>
        <table id="users" class="table table-dark" style="width:100%">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Permissões</th>
                    <th>Extensões</th>
                </tr>
            </thead>
            <tbody>
                <tr :key="user.id" v-for="user in users">
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>
                        {{ user.permission_names }}
                        <a href="#0" @click="editPermissions(user.id)">Editar</a>
                    </td>
                    <td>
                        {{ user.extension_numbers }}
                        <a href="#0" @click="editExtensions(user.id)">Editar</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <modal v-if="showExtensionsModal === true && selected_user !== null && extensions !== null">
        <h3 slot="header">Editar Extensões</h3>
        <div slot="body">
            <p>Utilizador: {{selected_user.name}} ({{selected_user.email}})</p>
            <select id="extensions_select" class="custom-select" multiple>
                <option :key="extension.id" v-for="extension in extensions" v-bind:value="extension.id">{{extension.number}}</option>
                <option value="none">Nenhuma</option>
            </select>
        </div>
        <div slot="footer">
            <button class="modal-close-button btn btn-secondary" @click="showExtensionsModal = false">Cancelar</button>
            <button class="modal-close-button btn btn-primary" @click="saveExtensions">Guardar</button>
        </div>
    </modal>
    <modal v-if="showPermissionsModal === true && selected_user !== null">
        <h3 slot="header">Editar Permissões</h3>
        <div slot="body">
            <p>Utilizador: {{selected_user.name}} ({{selected_user.email}})</p>
            <select id="permissions_select" class="custom-select" multiple>
                <option value="1">Administrador</option>
                <option value="2">Gestor</option>
                <option value="4">Gestão Operacional Integrada</option>
                <option value="5">SALOP</option>
                <option value="6">COVID-19 Callbacks</option>
                <option value="7">COVID-19 Patient Information</option>
                <option value="8">COVID-19 Results</option>
                <option value="none">Nenhuma</option>
            </select>
        </div>
        <div slot="footer">
            <button class="modal-close-button btn btn-secondary" @click="showPermissionsModal = false">Cancelar</button>
            <button class="modal-close-button btn btn-primary" @click="savePermissions">Guardar</button>
        </div>
    </modal>
</div>
</template>

<script>
import Modal from "../../components/Modal";

export default {
    components: {
        Modal,
    },
    beforeRouteEnter: function (to, from, next) {
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
                    next({
                        path: "/unauthorized"
                    });
                }
            });
    },
    data() {
        return {
            loading: true,
            users: null,
            extensions: null,
            selected_user: null,
            showExtensionsModal: false,
            showPermissionsModal: false,
        };
    },
    created() {
        this.fetchData();
    },
    methods: {
        editPermissions(user_id) {
            this.selected_user = this.users.filter((val) => {
                return val.id == user_id;
            })[0];
            this.showPermissionsModal = true;
        },
        savePermissions() {
            let that = this;
            let permissions = $("#permissions_select").val();
            axios
                .post(
                    location.protocol +
                    "//" +
                    process.env.MIX_AUTH_API +
                    "/users/" +
                    this.selected_user.id +
                    "/permissions", {
                        permissions,
                    }
                )
                .then(function (response) {
                    that.fetchData();
                })
                .catch(function (error) {
                    console.log(error);
                });
            this.showPermissionsModal = false;
            this.selected_user = null;
            this.users = null;
            this.loading = true;
        },
        editExtensions(user_id) {
            this.selected_user = this.users.filter((val) => {
                return val.id == user_id;
            })[0];
            this.showExtensionsModal = true;
        },
        saveExtensions() {
            let that = this;
            let extensions = $("#extensions_select").val();
            axios
                .post(
                    location.protocol +
                    "//" +
                    process.env.MIX_SALOP_API +
                    "/users/" +
                    this.selected_user.id +
                    "/extensions", {
                        extensions,
                    }
                )
                .then(function (response) {
                    that.fetchData();
                })
                .catch(function (error) {
                    console.log(error);
                });
            this.showExtensionsModal = false;
            this.selected_user = null;
            this.users = null;
            this.loading = true;
        },
        fetchData() {
            axios
                .get(location.protocol + "//" + process.env.MIX_AUTH_API + "/users/")
                .then((response) => {
                    this.users = response.data;
                    this.loading = false;
                });
            axios
                .get(
                    location.protocol +
                    "//" +
                    process.env.MIX_SALOP_API +
                    "/extensions/numbers"
                )
                .then((response) => {
                    this.extensions = response.data;
                });
        },
    },
    watch: {
        users: function (val) {
            if (val === null) {
                $("#users_wrapper").remove();
            } else {
                Vue.nextTick(() => {
                    $("#users").DataTable();
                });
            }
        },
    },
};
</script>

<style>
.pagination {
    float: right;
}

.dataTables_filter {
    float: right;
    width: 100%;
}

.dataTables_filter,
.dataTables_filter label,
.dataTables_filter label .form-control {
    width: 100%;
}
</style>
