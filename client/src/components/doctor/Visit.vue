<template>
    <div v-if="isOpen" class="modal">
        <div class="modal-content">
            <span class="close" @click="closeModal">&times;</span>
            <div>
                <h5>{{ visit.day }}, {{ visit.startTime }}-{{ visit.endTime }}</h5>
                <Error v-if="error" :error="error"/>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Patient:
                        <span>{{ visit.patientFirstName }} {{ visit.patientLastName }}</span></li>
                    <li class="list-group-item">Pesel:
                        <span>{{ visit.patientPesel }}</span></li>
                    <li class="list-group-item">Insurance:
                        <span>{{ visit.patientInsurance }}</span></li>
                    <li class="list-group-item">
                        <div class="dropdown">
                            <button @click="getOffices"
                                    class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Choose an available office
                            </button>
                            <span class="ml-4" v-if="selectedOffice">
                                Your office: {{ selectedOffice.number }} - {{ selectedOffice.name }}
                            </span>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <button class="dropdown-item"
                                        v-for="office in offices" :key="office.id"
                                        @click="selectOffice(office.id)">
                                    {{ office.number }}: {{ office.name }}
                                </button>
                            </div>
                        </div>
                    </li>
                    <li v-if="selectedOffice" class="list-group-item">
                        <button @click="startVisit" class="btn btn-block btn-outline-danger">Start visit</button>
                    </li>
                    <li class="list-group-item" v-if="visitStart">
                        <form @submit.prevent="finishVisit">
                            <h5 class="mb-2">{{ visit.patientFirstName }} {{ visit.patientLastName }} medical record</h5>
                            <Error v-if="error" :error="error"/>
                            <div class="form-group">
                                <label>Name</label>
                                <input v-model="name"
                                       type="text"
                                       class="form-control"
                                       placeholder="name">
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Record</label>
                                <textarea v-model="description"
                                          class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                            </div>

                            <button class="btn btn-primary btn-block">Finish the visit</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import Error from "@/components/Error.vue";
import axios from "axios";
import {SERVER} from "@/config";
import {mapGetters} from "vuex";
import router from "@/router";
import {markRaw} from "vue";

export default {
    components: { Error},

    computed: {
        ...mapGetters(['user'])
    },

    data() {
        return {
            token: localStorage.getItem('token'),
            error: '',
            selectedOffice: null,
            offices: [],
            visitStart: false,
            name: '',
            description: '',
        }
    },

    props: {
        isOpen: {
            required: true
        },
        visit: {
            required: true
        },
        updateVisit: {
            type: Function,
            required: true
        },
    },

    methods: {
        async finishVisit() {
            const officeId = this.selectedOffice.id
            await this.updateOffice(officeId);

            const medicalRecordData = {
                name: this.name,
                description: this.description,
                patientId: this.visit.patientId,
                visitId: this.visit.id
            };
            const medicalRecordResponse = await axios.post(`${SERVER}/record/create`, medicalRecordData, {
                headers: { Authorization: 'Bearer ' + this.token }
            });

            if (medicalRecordResponse.status === 201) {
                const updateVisitResponse = await axios.patch(`${SERVER}/visit/update/${this.visit.id}`, null, {
                    headers: { Authorization: 'Bearer ' + this.token }
                });
                this.updateVisit();
                this.closeModal();
                this.visitStart = false;
                this.selectedOffice = null;
                this.name = '';
                this.description = '';
            }
        },

        async startVisit() {
            await this.updateOffice(this.selectedOffice.id);
            this.visitStart = true;
        },

        async updateOffice(officeId) {
            await axios.patch(`${SERVER}/office/update/${officeId}`, null, {
                headers: { Authorization: 'Bearer ' + this.token }
            });
        },

        selectOffice(i) {
            this.selectedOffice = this.offices.find(elem => elem.id === i);
        },

        async getOffices() {
            const response = await axios.get(`${SERVER}/office/show`, {
                headers: { Authorization: 'Bearer ' + this.token }
            });
            this.offices = response.data.filter(office => office.available === true);
        },

        closeModal() {
            this.$emit('close');
        }
    }
};
</script>

<style scoped>
.modal {
    display: flex;
    position: fixed;
    z-index: 9;
    width: 100%;
    height: 100%;
    align-items: center;
    justify-content: center;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
}

.modal-content {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    max-width: 600px;
    transition: all .3s;
}

h5 {
    text-align: center;
}

.close {
    position: absolute;
    top: 10px;
    right: 20px;
    cursor: pointer;
}
</style>
