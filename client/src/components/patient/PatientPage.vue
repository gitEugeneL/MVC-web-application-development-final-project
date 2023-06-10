<template>
    <div class="auth-inner">
        <h3>Hello: {{ this.user.firstName }}</h3>
        <div>
            <div class="office">
                <button class="btn btn-outline-primary" @click="pastVisitsButton">Past visits</button>
                <button class="btn btn-outline-primary" @click="futureVisitsButton">Future visits</button>
            </div>
            <div>
                <button class="btn btn-block btn-outline-success" @click="newVisitButton">New visit</button>
                <button class="btn btn-block btn-outline-dark" @click="medicalRecordButton">My medical records</button>
            </div>

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
        async pastVisitsButton() {
            await router.push('/patient/past-visits');
        },
        async futureVisitsButton() {
            await router.push('/patient/future-visits');
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