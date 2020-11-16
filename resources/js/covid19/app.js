require('../bootstrap');
window.Vue = require('vue');
window.VueRouter = require('vue-router').default;
Vue.use(VueRouter);

window.cleanAxios = require('axios');

import Sidebar from './components/Sidebar'
import Index from './components/Index'
import CallBacks from './components/CallBacks'
import CallFlow from './components/CallFlow'
import Unauthorized from './components/Unauthorized'
import NotFound from './components/NotFound'

const routes = [{
        path: '/',
        component: Index,
        meta: {
            keepAlive: false
        }
    },
    {
        path: '/callbacks',
        component: CallBacks,
        meta: {
            keepAlive: false
        }
    },
    {
        path: '/callflow',
        component: CallFlow,
        meta: {
            keepAlive: false
        }
    },
    {
        path: '/unauthorized',
        component: Unauthorized,
        meta: {
            keepAlive: false
        }
    },
    {
        path: '*',
        component: NotFound
    }
]

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
})


async function start() {
    window.app = new Vue({
        router,
        components: {
            Sidebar
        }
    }).$mount('#app');
}

start();
