<template>
    <li class="nav-item">
        <a href="javascript:void(0)" @click="handleClickLogout" data-toggle="modal"
           data-target="#deleteModal" class="nav-link text-danger">Logout</a>
    </li>
</template>

<script>
import router from "@/router";
import store from "@/vuex";

export default {
    name: "Logout",

    methods: {
        async handleClickLogout() {
            localStorage.removeItem("token");
            await store.dispatch('user', null);
            await store.dispatch('role', null);
            await router.push('/auth');
        }
    }
}
</script>