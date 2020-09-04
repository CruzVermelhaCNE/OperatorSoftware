require('../bootstrap');
window.Vue = require('vue');

import Login from './components/Login'

const app = new Vue({
    el: '#app',
    components: { Login }
});
