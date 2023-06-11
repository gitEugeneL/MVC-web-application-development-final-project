<template>
    <div class="container-lg">
        <div class="card mb-4">
            <h4 class="card-title mb-4 mt-3">Your visits</h4>
            <div class="block-1 d-flex flex-wrap d-flex justify-content-center">
                <button
                    @click="getCompletedVisit"
                    :class="['mr-3', 'mb-3', 'btn', 'btn-outline-primary', { 'active': completedVisits !== null }]">
                    Completed visits
                </button>
                <button
                    @click="getFutureVisits"
                    :class="['mr-3', 'mb-3', 'btn', 'btn-outline-primary', { 'active': futureVisits !== null }]">
                    Future visits
                </button>
            </div>
        </div>

        <div class="d-flex flex-wrap justify-content-center" v-if="futureVisits">
            <div v-for="visit in futureVisits" :key="visit.id">
               <div @click="visit.visitForm = true" class="card card-visit">
                   <b class="card-title">{{ visit.day }}, {{ visit.startTime }}-{{visit.endTime}}</b>
                   <span class="card-subtitle">Insurance: {{ visit.patientInsurance }}</span>
                   <i>{{ visit.patientFirstName }} {{ visit.patientLastName }}</i>
                   <i>{{ visit.patientPhone }} </i>
                   <i>{{ visit.patientEmail }}</i>
               </div>
                <Visit :isOpen="visit.visitForm" :visit="visit" :updateVisit="updateVisitList"
                       @close="visit.visitForm = false" />
            </div>
        </div>

        <div class="d-flex flex-wrap justify-content-center" v-if="completedVisits">
            <div v-for="visit in completedVisits" :key="visit.id">
                <div @click="visit.visitForm = true" class="card">
                    <b class="card-title">{{ visit.day }}, {{ visit.startTime }}-{{visit.endTime}}</b>
                    <i>{{ visit.patientFirstName }} {{ visit.patientLastName }}</i>
                    <i>{{ visit.patientPhone }} </i>
                    <i>{{ visit.patientEmail }}</i>
                </div>
            </div>
        </div>


    </div>
</template>


<script>
import axios from "axios";
import {SERVER} from "@/config";
import MakeAppointment from "@/components/patient/MakeAppointment.vue";
import Visit from "@/components/doctor/Visit.vue";
import EditSpecialization from "@/components/manager/EditSpecialization.vue";

export default {
    name: "DoctorPage",
    components: {EditSpecialization, Visit, MakeAppointment},

    data() {
        return {
            token: localStorage.getItem('token'),
            futureVisits: null,
            completedVisits: null,
        }
    },

    methods: {
        async getFutureVisits() {
            this.completedVisits = null;
            const visits = await this.getVisits();
            this.futureVisits = visits.filter(visit => visit.completed === false);
        },

        async getCompletedVisit() {
            this.futureVisits = null;
            const visits = await this.getVisits();
            this.completedVisits = visits.filter(visit => visit.completed === true);
        },

        async getVisits() {
            const response = await axios.get(`${SERVER}/visit/show-doctor-future`, {
                headers: { Authorization: 'Bearer ' + this.token }
            });
            return response.data;
        },

        async updateVisitList() {
            await this.getFutureVisits();
        }
    }
}


</script>
<style scoped>
    h4 {
        text-align: center;
    }

    .card {
        box-shadow: 0 5px 10px rgba(34, 35, 58, .2);
        padding: 10px;
        border-radius: 10px;
        transition: all .3s;
        margin-bottom: 15px;
        margin-right: 10px;
        min-width: 285px;
    }

    .card-visit:hover,
    .card-visit:focus {
        border: 1px solid #007afd;
        cursor: pointer;
        box-shadow: none;
    }
</style>