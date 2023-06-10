<template>
    <div class="container-lg">
        <div class="card mb-4">
            <h4 class="card-title mb-4 mt-3">Schedule a new visit</h4>
            <div class="block-1 d-flex flex-wrap d-flex justify-content-center">
                <button
                        v-for="specialization in specializations"
                        :key="specialization.id"
                        @click="getDoctors(specialization.id)"
                        :class="['mr-3', 'mb-3', 'btn', 'btn-outline-primary',
                        { active: activeButton === specialization.id }]">
                    {{ specialization.name }}
                </button>
            </div>
        </div>

        <div class="block-2 row d-flex flex-wrap justify-content-center align-items-start">
            <div class="col-3 card" v-if="activeButton">
                <p class="card-title">Doctors</p>
                    <div v-for="doctor in doctors" :key="doctor.id" class="card doctor-card"
                         @click="doctorButton(doctor)"
                         :class="['card', 'doctor-card', { active: activeButtonDoctor === doctor.id }]">
                        <b class="card-subtitle ">{{ doctor.firstName }} {{ doctor.lastName }}</b>
                        <div class="d-flex flex-wrap">
                            <small class="mr-2" v-for="s in doctor.specialization">{{ `${s} ` }}</small>
                        </div>
                    </div>
            </div>

            <div class="block-3 card ml-2" v-if="activeButtonDoctor">
                <flat-pickr v-model="selectedDate" :config="flatpickrOptions" @change="dateClick"/>
            </div>

            <div class="block-4 card col-4 ml-2" v-if="selectedDate">
                <p class="card-title">Available time: <span>{{ this.selectedDate }}</span></p>

                <div v-for="time in availableTime" :key="time.start"
                     @click="visitButton(time.start)"
                    :class="['d-flex', 'card', 'doctor-card', { active: startTime === time.start }]">
                    {{time.start}} - {{time.end}}
                </div>
                <button v-if="startTime" class="mr-3 mb-3 btn btn-outline-primary"
                        @click="makeAppointment = true">
                        Make an appointment</button>

                <MakeAppointment
                        :isOpen="makeAppointment"
                        :doctor="doctor"
                        :day="selectedDate"
                        :startTime="startTime"
                        @close="makeAppointment = false" />
            </div>
        </div>
    </div>
</template>


<script>
import FlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import NewSpecialization from "@/components/manager/NewSpecialization.vue";
import NewDoctor from "@/components/manager/NewDoctor.vue";
import axios from "axios";
import {SERVER} from "@/config";
import EditSpecialization from "@/components/manager/EditSpecialization.vue";
import MakeAppointment from "@/components/patient/MakeAppointment.vue";

export default {
    name: "NewVisit",
    components: {MakeAppointment, EditSpecialization, NewDoctor, NewSpecialization, FlatPickr},

    data() {
        return {
            token: localStorage.getItem('token'),
            activeButton: null,
            activeButtonDoctor: null,
            selectedDate: null,
            flatpickrOptions: {
                dateFormat: 'Y-m-d',
                minDate: new Date().fp_incr(1),
                maxDate: new Date().fp_incr(30),
                inline: true,
                disable: [
                    function(date) {
                        return (date.getDay() === 6 || date.getDay() === 0);
                    }
                ]
            },
            specializations: [],
            doctors: [],
            doctorId: null,
            doctor: null,
            availableTime: [],
            startTime: '',
            makeAppointment: false
        }
    },

    created() {
        this.getSpecializations();
    },

    methods: {
        visitButton(start) {
            this.startTime = start;
        },

        async dateClick() {
            this.startTime = '';
            const data = {
                doctorId: this.doctorId,
                day: this.selectedDate
            };
            if (this.selectedDate) {
                const response = await axios.post(`${SERVER}/visit/time`, data, {
                    headers: {Authorization: 'Bearer ' + this.token}
                });
                this.availableTime = response.data;
            }
        },

        async getSpecializations() {
            const response = await axios.get(`${SERVER}/specialization/show`, {
                headers: { Authorization: 'Bearer ' + this.token }
            });
            this.specializations = response.data;
        },

        async getDoctors(id) {
            this.selectedDate = null;
            this.activeButtonDoctor = null;
            this.activeButton = id;
            const response = await axios.get(`${SERVER}/doctor/show/${id}`, {
                headers: { Authorization: 'Bearer ' + this.token }
            });
            this.doctors = response.data;
        },

        async doctorButton(doctor) {
            this.doctor = doctor;
            this.activeButtonDoctor = doctor.id;
            this.doctorId = doctor.id;
            this.selectedDate = null;
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
}

.doctor-card {
    box-shadow: none;
}

.doctor-card:focus,
.doctor-card:hover {
    cursor: pointer;
    box-shadow: 0 5px 10px rgba(34, 35, 58, .2);
}

.active {
    background-color: rgb(0, 122, 253);
    box-shadow: 0 5px 10px rgba(34, 35, 58, .2);
    color: white;
}

.active small {
    color: white;
}

/*.block-4 {*/
/*    !*min-width: 180px;*!*/
/*}*/


</style>