require('../bootstrap');
window.Vue = require('vue');
window.VueRouter = require('vue-router').default;
Vue.use(VueRouter);

import Sidebar from './components/Sidebar'
import Index from './components/Index'
import Phones from './components/Phones'
import Door from './components/Door'
import Users from './components/Users'
import Reports from './components/Reports'
import Extensions from './components/Extensions'
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
        path: '/phones',
        component: Phones,
        meta: {
            keepAlive: true
        }
    },
    {
        path: '/door',
        component: Door,
        meta: {
            keepAlive: false
        }
    },
    {
        path: '/users',
        component: Users,
        meta: {
            keepAlive: false
        }
    },
    {
        path: '/reports',
        component: Reports,
        meta: {
            keepAlive: false
        }
    },
    {
        path: '/extensions',
        component: Extensions,
        meta: {
            keepAlive: false
        },
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
