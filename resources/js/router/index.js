import { createWebHistory, createRouter } from "vue-router";

import Home from '../components/views/Home';
import PollCreate from '../components/poll/Create';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home
    },
    {
        name: 'pollCreate',
        path: '/poll/create',
        component: PollCreate
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
    linkExactActiveClass: 'active',
});

export default router;
