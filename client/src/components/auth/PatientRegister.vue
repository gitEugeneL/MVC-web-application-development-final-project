<template>
    <div class="auth-inner">
        <form @submit.prevent="handleSubmit">
            <Error v-if="error" :error="error"/>
            <h3>Patient registration</h3>
            <div class="form-group">
                <label>Email</label>
                <input v-model="email"
                       type="email"
                       class="form-control"
                       placeholder="Your email address">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input v-model="password"
                       type="password"
                       class="form-control"
                       placeholder="Password">
            </div>
            <div class="form-group">
                <label>Confirm password</label>
                <input v-model="confirmPassword"
                       type="password"
                       class="form-control"
                       placeholder="Confirm password">
            </div>
            <div class="form-group">
                <label>First Name</label>
                <input v-model="firstName"
                       type="text"
                       class="form-control"
                       placeholder="Your first name">
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input v-model="lastName"
                       type="text"
                       class="form-control"
                       placeholder="Your first name">
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input v-model="phone"
                       type="text"
                       class="form-control"
                       placeholder="Your first name">
            </div>
            <div class="form-group">
                <label>Date of birth</label>
                <input v-model="dateOfBirth"
                       type="date"
                       class="form-control"
                       placeholder="Your first name">
            </div>
            <div class="form-group">
                <label>Pesel</label>
                <input v-model="pesel"
                       type="number"
                       class="form-control"
                       placeholder="Your first name">
            </div>
            <div class="form-group">
                <label>Insurance</label>
                <input v-model="insurance"
                       type="text"
                       class="form-control"
                       placeholder="Your first name">
            </div>
            <button class="btn btn-primary btn-block">Sign up</button>
        </form>
    </div>
</template>


<script>
import Error from "@/components/Error.vue";
import axios from "axios";
import {SERVER} from "@/config";
import router from "@/router";
import store from "@/vuex";

export default {
    name: "PatientRegister",
    components: {Error},

    data() {
        return { email: '', password: '', confirmPassword: '', firstName: '', lastName: '',
            phone: '', dateOfBirth: '', pesel: '', insurance: '', error: '' };
    },

    methods: {
        async handleSubmit() {
            if (this.password !== this.confirmPassword) {
                this.error = 'Passwords don\'t match';
                return;
            }
            this.error = '';

            const data = {
                firstName: this.firstName,
                lastName: this.lastName,
                dateOfBirth: this.dateOfBirth,
                pesel: this.pesel.toString(),
                phone: this.phone,
                insurance: this.insurance,
                password: this.password,
                email: this.email
            };

            try {
                await axios.post(`${SERVER}/patient/create`, data);
                await store.dispatch('role', 'patient');
                await router.push('/auth/login');
            } catch (e){
                this.error = e.response.data.detail;
            }
        }
    }
}
</script>


<style scoped>
    .auth-inner {
        margin-bottom: 20px;
    }
</style>