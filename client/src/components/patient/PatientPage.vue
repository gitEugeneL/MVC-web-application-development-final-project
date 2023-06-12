<template>
    <div class="auth-inner">
        <h3 v-if="user">Hello: {{ user.firstName }}</h3>
        <div>
            <button class="btn btn-block btn btn-success mb-4 mt-4" @click="newVisitButton">New visit</button>
            <button class="btn btn-block btn-outline-primary" @click="VisitsButton">My visits</button>
            <button class="btn btn-block btn-outline-info" @click="medicalRecordButton">My medical records</button>
        </div>

    </div>
</template>


<script>
import {mapGetters} from "vuex";
import router from "@/router";

export default {
    name: "PatientPage",

    computed: {
        ...mapGetters(['user'])
    },

    methods : {
        async VisitsButton() {
            await router.push('/patient/visits');
        },
        async newVisitButton() {
            await router.push('/patient/new-visit');
        },
        async medicalRecordButton() {
            await router.push('/patient/medical-records');
        }
    }
}
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