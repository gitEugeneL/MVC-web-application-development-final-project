<template>
    <div class="container">
        <div class="row">
            <div class="block1 col-6">
                <div class="card">
                    <h4 class="card-title">Specializations</h4>
                    <div class="card-text">
                        <button
                            v-for="specialization in specializations"
                            :key="specialization.id"
                            @click="getDoctors(specialization.id, specialization.name)"
                            :class="['btn', 'btn-outline-dark', 'btn-block', 'mb-3',
                                { active: activeButton === specialization.id }]">
                                    {{ specialization.name }}
                        </button>
                        <div class="text-center mb-2 mt-4">
                            <button class="col-5 mr-4 btn btn-outline-primary"
                                    @click="openNewSpecialization">new specialization
                            </button>
                            <NewSpecialization :isOpen="newSpecializationOpen" @close="closeNewSpecialization" />

                            <button class="col-5 btn btn-outline-primary"  @click="openNewDoctor">new doctor</button>
                            <NewDoctor :specializations="specializations" :isOpen="newDoctorOpen" @close="closeNewDoctor" />
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h4 class="card-title">Offices</h4>
                    <ul class="list-group list-group-flush">
                        <li v-for="office in offices" :key="office.id" class="d-flex list-group-item">
                            <span>{{ office.number }} - {{ office.name }}</span>
                            <button v-if="office.available"
                                    @click="updateOffice(office.id)" class="btn btn-outline-danger">
                                deactivate
                            </button>
                            <button v-else @click="updateOffice(office.id)" class="btn btn-outline-success">
                                activate
                            </button>
                        </li>
                    </ul>
                    <div class="card-body">
                        <button @click="openNewOffice" class="btn btn-outline-primary">add office</button>
                        <NewOffice :isOpen="newOfficeOpen" @close="closeNewOffice" />
                    </div>
                </div>
            </div>
                <div class="block1 col-6">
                    <div class="card">
                    <h4 class="card-title">{{ this.specName }}</h4>

                    <div v-for="doctor in doctors" :key="doctor.id" class="card doctor-card">
                        <h5 class="card-title">{{ doctor.firstName }} {{ doctor.lastName }}</h5>
                        <p class="card-subtitle mb-2 text-muted">
                            <span v-for="s in doctor.specialization">{{ `${s} ` }}</span>
                        </p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Email: {{ doctor.email }}</li>
                            <li class="list-group-item">Phone: {{ doctor.phone }}</li>
                        </ul>
                        <div class="card-body">
                            <button class="btn btn-outline-primary"
                                    @click="doctor.editSpecializationOpen = true">edit specializations
                            </button>
                            <EditSpecialization
                                    :specializations="specializations"
                                    :doctor="doctor"
                                    :isOpen="doctor.editSpecializationOpen"
                                    @close="doctor.editSpecializationOpen = false" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>


<script>
import {SERVER} from "@/config";
import axios from "axios";
import NewSpecialization from "@/components/manager/NewSpecialization.vue";
import NewDoctor from "@/components/manager/NewDoctor.vue";
import NewOffice from "@/components/manager/NewOffice.vue";
import EditSpecialization from "@/components/manager/EditSpecialization.vue";

export default {
    components: {EditSpecialization, NewDoctor, NewSpecialization, NewOffice },

    data() {
        return {
            token: localStorage.getItem('token'),
            specializations: [],
            doctors: [],
            offices: [],
            specName: 'All doctors',
            activeButton: null,
            newSpecializationOpen: false,
            newOfficeOpen: false,
            newDoctorOpen: false,
            editSpecializationOpen: false

        };
    },

    created() {
        this.getSpecializations();
        this.getAllDoctors();
        this.getOffices();
    },

    methods: {
        async updateOffice(officeId) {
            try {
                await axios.patch(`${SERVER}/office/update/${officeId}`, null, {
                    headers: { Authorization: 'Bearer ' + this.token }
                });
                await this.getOffices();
            } catch (error) {}
        },

        async getOffices() {
            const response = await axios.get(`${SERVER}/office/show`, {
                headers: { Authorization: 'Bearer ' + this.token }
            });
            this.offices = response.data;
        },

        async getSpecializations() {
            const response = await axios.get(`${SERVER}/specialization/show`, {
                headers: { Authorization: 'Bearer ' + this.token }
            });
            this.specializations = response.data;
        },

        async getDoctors(id, specializationName) {
            this.activeButton = id;
            const response = await axios.get(`${SERVER}/doctor/show/${id}`, {
                headers: { Authorization: 'Bearer ' + this.token }
            })
            this.doctors = response.data;
            this.specName = specializationName.charAt(0).toUpperCase() + specializationName.slice(1);
        },

        async getAllDoctors() {
            const response = await axios.get(`${SERVER}/doctor/show`, {
                headers: { Authorization: 'Bearer ' + this.token }
            })
            this.doctors = response.data;
        },

        openNewSpecialization() {
            this.newSpecializationOpen = true;
        },

        closeNewSpecialization() {
            this.getSpecializations();
            this.newSpecializationOpen = false;
        },

        openNewOffice() {
            this.newOfficeOpen = true;
        },

        openNewDoctor() {
            this.newDoctorOpen = true;
        },

        closeNewDoctor() {
            this.newDoctorOpen = false;
        },

        closeNewOffice() {
          this.getOffices();
          this.newOfficeOpen = false;
        }
    }
};
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
    }

    .doctor-card {
        border-radius: 0;
        box-shadow: 0 2px 2px rgba(34, 35, 58, .1);
    }

    .list-group-item {
        align-items: center;
        justify-content: space-between;
    }
</style>
