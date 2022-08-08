require('./bootstrap');
import Vue from 'vue';
window.Vue = require('vue');
import VueRouter from "vue-router";

Vue.use(VueRouter);

import PostsIndex from './components/PostsIndex.vue';
import PostsCreate from './components/PostsCreate.vue';
import PostsEdit from './components/PostsEdit.vue';

const routes = [
    {path: '/posts', component: PostsIndex, name: 'indexPosts'},
    {path: '/posts/filter/:filter(.*)', component: PostsIndex, name: 'indexPosts'},
    {path: '/posts?create', component: PostsCreate, name: 'createPost'},
    {path: '/posts?edit=:id', component: PostsEdit, name: 'editPost'},
]

const router = new VueRouter({
    mode: 'history',
    hash: false,
    routes
})
Vue.component('pagination', require('laravel-vue-pagination'));
const app = new Vue({ router }).$mount('#app')
