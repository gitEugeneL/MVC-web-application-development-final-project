<template>
    <nav class="navbar navbar-expand-md navbar-light fixed-top">
        <div class="container">
            <router-link v-if="this.user && this.role === 'manager'" to="/manager" class="navbar-brand">
                {{ this.role}}: {{ this.user.firstName }} {{ this.user.lastName }}
            </router-link>
            <router-link v-if="this.user && this.role === 'patient'" to="/patient" class="navbar-brand">
                {{ this.role}}: {{ this.user.firstName }} {{ this.user.lastName }}
            </router-link>
            <button
                    class="navbar-toggler"
                    type="button"
                    data-toggle="collapse"
                    data-target="#navbarSupportedContent"
                    @click="toggleNavbar"
                    :aria-expanded="navbarExpanded ? 'true' : 'false'"
                    :class="{ collapsed: !navbarExpanded }"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" :class="{ show: navbarExpanded }">
<!--                <ul v-if="user" class="navbar-nav mr-auto">-->
<!--                    <li class="nav-item">-->
<!--                        <router-link to="/my-auctions" class="nav-link" @click="closeNavbar">My auctions</router-link>-->
<!--                    </li>-->
<!--                    <li class="nav-item">-->
<!--                        <router-link to="/im-participant" class="nav-link" @click="closeNavbar">I'm participate</router-link>-->
<!--                    </li>-->
<!--                    <li class="nav-item">-->
<!--                        <router-link to="/im-winner" class="nav-link" @click="closeNavbar">I'm winner</router-link>-->
<!--                    </li>-->
<!--                    <li class="nav-item">-->
<!--                        <router-link to="/my-purchased-auctions" class="nav-link" @click="closeNavbar">Purchased auctions</router-link>-->
<!--                    </li>-->
<!--                    <li class="nav-item">-->
<!--                        <router-link to="/my-sold-products" class="nav-link" @click="closeNavbar">My sold products</router-link>-->
<!--                    </li>-->
<!--                </ul>-->

                <ul v-if="!user" class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <router-link to="/auth" class="nav-link" @click="closeNavbar">Login</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/auth/patient-register" class="nav-link" @click="closeNavbar">Sign up</router-link>
                    </li>
                </ul>
                <ul v-if="user" class="navbar-nav ml-auto">
                    <Logout @click="closeNavbar" />
                </ul>
            </div>
        </div>
    </nav>
</template>


<script>
import { mapGetters } from "vuex";
import Logout from "@/components/auth/Logout.vue";

export default {
    name: 'NavBar',
    components: {Logout},
    computed: {
        ...mapGetters(['user', 'role'])
    },

    data() {
        return {
            navbarExpanded: false,
        }
    },

    methods: {
        toggleNavbar() {
            this.navbarExpanded = !this.navbarExpanded;
        },

        closeNavbar() {
            this.navbarExpanded = false;
        }
    }
}
</script>


<style scoped>
.navbar-light {
    background-color: #fff;
    box-shadow: 0 14px 80px rgba(34, 35, 58, .2);
    margin-bottom: 20px;
}
</style>

