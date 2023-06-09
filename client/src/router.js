import {createRouter, createWebHistory} from "vue-router";
import AuthPage from "@/components/auth/AuthPage.vue";
import Login from "@/components/auth/Login.vue";
import ManagerPage from "@/components/manager/ManagerPage.vue";
import PatientRegister from "@/components/auth/PatientRegister.vue";


const routes = [
    { path: '/auth', component: AuthPage },
    { path: '/auth/login', component: Login },
    { path: '/auth/patient-register', component: PatientRegister },



    { path: '/manager', component: ManagerPage },


]


const router = createRouter({
    history: createWebHistory(),
    routes
})


router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');
    if (!token && to.path !== '/auth' && to.path !== '/auth/login' && to.path !== '/auth/patient-register') {
        next('/auth');
    } else {
        next();
    }
});


export default router;