<template>
    <div class="auth-inner">
        <form @submit.prevent="handleSubmit">
            <Error v-if="error" :error="error"/>
            <h3>Login: {{ this.role }}</h3>
            <div class="form-group">
                <label>Email</label>
                <input v-model="email"
                       type="email"
                       class="form-control"
                       placeholder="Email">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input v-model="password"
                       type="password"
                       class="form-control"
                       placeholder="Password">
            </div>
            <div class="redirect">
                <router-link to="/auth/patient-register" class="text-primary">if you don't have an account</router-link>
            </div>
            <button class="btn btn-primary btn-block">Login</button>
        </form>
    </div>
</template>


<script>
import axios from "axios";
import {SERVER} from "@/config";
import {mapGetters} from "vuex";
import Error from "@/components/Error.vue";
import store from "@/vuex";
import router from "@/router";

export default {
    name: 'Login',

    components: { Error },

    computed: {
        ...mapGetters(['role'])
    },

    data() {
        return {
            email: '',
            password: '',
            error: '',
        }
    },

    methods: {
        async handleSubmit() {
            try {
                const data = {
                    username: this.email,
                    password: this.password
                }
                const response = await axios.post(`${SERVER}/login`, data);

                if (response.status === 200) {
                    localStorage.setItem('token', response.data.token);

                    const getAuthUser = await axios.get(`${SERVER}/${this.role}/info`, {
                        headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
                    });
                    if (getAuthUser.status === 200) {
                        await store.dispatch('user', getAuthUser.data);

                        if (this.role === 'manager') {
                            await router.push('/manager');
                        } else if (this.role === 'doctor') {
                            await router.push('/doctor');
                        } else if (this.role === 'patient') {
                            await router.push('/patient');
                        }
                    }
                }

            } catch (e) {
                if (e.response.status === 401) {
                    this.error = "Login or password is not correct";
                }
            }
        },
    }
}
</script>


<style scoped>
    .redirect {
        text-align: center;
        margin-bottom: 10px;
    }
</style>