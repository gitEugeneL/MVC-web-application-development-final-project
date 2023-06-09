<template>
    <div class="auth-inner">
        <h3>Login</h3>
        <div>
            <div class="office">
                <button class="btn btn-outline-primary" @click="managerButton">Manager</button>
                <button class="btn btn-outline-primary" @click="doctorButton">Doctor</button>
            </div>
            <div>
                <button class="btn btn-block btn-outline-success" @click="patientButton">Patient</button>
                <button class="btn btn-block btn-outline-dark" @click="registerButton">Register</button>
            </div>

        </div>
    </div>
</template>


<script>
import store from "@/vuex";
import router from "@/router";

export default {
    name: "AuthPage",

    methods : {
        async managerButton() {
            await store.dispatch('role', 'manager');
            await router.push('/auth/login');
        },
        async doctorButton() {
            await store.dispatch('role', 'doctor');
            await router.push('/auth/login');
        },
        async patientButton() {
            await store.dispatch('role', 'patient');
            await router.push('/auth/login');
        },
        async registerButton() {
            await router.push('/auth/patient-register');
        }
    }
};
</script>

<style scoped>
    .office {
        display: flex;
        justify-content: space-between;
        margin-top: 25px;
        margin-bottom: 15px;
    }

    .office button {
        width: 160px;
    }


    .auth-inner .one {
        margin-top: 30px;
    }

    .auth-inner button {
        margin-bottom: 15px;
    }

</style>
