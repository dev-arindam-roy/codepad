import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import codepad from './components/codePadComponent.vue'
import addtab from './components/createTabComponent.vue'
import notFound from './components/notFoundComponent.vue'

const _numericRegx = /^\d+$/
const _alphNumericRegx = /^[A-Za-z0-9-_]+$/
const _cardNumberRegx = /^[A-Z0-9]+$/

const webRoutes = new VueRouter({
    mode: 'history',
    linkExactActiveClass: 'active',
    base: process.env.BASE_URL,
    routes: [
        {
            path: '/',
            component: codepad,
            name: 'root.index',
        },
        {
            path: '/create-tab',
            component: addtab,
            name: 'create.tab',
        },
        {
            path: '/404',
            component: notFound,
            name: '404',
        },
        {
            path: '*',
            component: notFound
        }
    ]
});
export default webRoutes;