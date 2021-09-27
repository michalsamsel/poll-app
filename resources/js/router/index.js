import { createWebHistory, createRouter } from "vue-router";

import Home from '../components/views/Home';
import PollCreate from '../components/polls/Create';
import PollShow from '../components/polls/Show';
import ResultShow from '../components/results/Show';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home
    },
    {
        name: 'pollShow',
        path: '/poll/:id',
        component: PollShow
    },
    {
        name: 'pollCreate',
        path: '/poll/create',
        component: PollCreate
    },
    {
        name: 'resultShow',
        path: '/poll/:id/result',
        component: ResultShow
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
    linkExactActiveClass: 'active',
});

export default router;
