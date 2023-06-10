<template>
    <div v-if="isOpen" class="modal">
        <div class="modal-content">
            <span class="close" @click="closeModal">&times;</span>
            <div class="mt-3">
                <h5>Specializations</h5>
                <Error v-if="error" :error="error"/>
                <ul class="list-group list-group-flush">
                    <li v-for="specialization in specializations" :key="specialization.id" class="d-flex list-group-item">
                        <span>{{ specialization.name }}</span>
                        <button v-if="doctor.specialization.includes(specialization.name)"
                                class="btn btn-outline-danger"
                                @click="deleteSpecialization(specialization.id)">delete
                        </button>
                        <button v-else class="btn btn-outline-success"
                                @click="addSpecialization(specialization.id)">add
                        </button>
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
import {reactive} from "vue";

export default {
    components: {Error},

    data() {
        return {
            error: '',
            reactiveSpecializations: reactive(this.specializations),
        }
    },

    props: {
        isOpen: {
            required: true
        },
        doctor: {
            required: true
        },
        specializations: {
            required: true,
            default: () => []
        }
    },

    methods: {
        async addSpecialization(specializationId) {
            const data = { specializationId };
            try {
                const response = await axios.patch(`${SERVER}/doctor/add-specialization/${this.doctor.id}`,
                    data, {
                        headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }
                });
                const addedSpecialization = this.specializations
                    .find(specialization => specialization.id === specializationId);

                if (addedSpecialization) {
                    this.doctor.specialization.push(addedSpecialization.name);
                }
            } catch (error) {
                this.error = error.response.data.detail;
            }
        },

        async deleteSpecialization(specializationId) {
            const data = { specializationId };
            try {
                const response = await axios.patch(`${SERVER}/doctor/delete-specialization/${this.doctor.id}`,
                    data, {
                    headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }
                });

                const deletedSpecialization = this.specializations
                    .find(specialization => specialization.id === specializationId);
                const index = this.reactiveSpecializations
                    .findIndex(specialization => specialization.id === specializationId);

                if (deletedSpecialization) {
                    this.doctor.specialization = this.doctor.specialization
                        .filter(name => name !== deletedSpecialization.name);
                    const newSpecializations = [...this.reactiveSpecializations];
                    newSpecializations.splice(index, 1);
                    this.reactiveSpecializations = newSpecializations;
                }
            } catch (error) {
                this.error = error.response.data.detail;
            }
        },

        closeModal() {
            this.$emit('close');
        }
    }
};
</script>


<style scoped>
.list-group-item {
    align-items: center;
    justify-content: space-between;
}

.modal {
    display: flex;
    position: fixed;
    z-index: 1;
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
    max-width: 400px;
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
