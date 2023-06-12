<template>
    <div v-if="isOpen" class="modal">
        <div class="modal-content">
            <span class="close" @click="closeModal">&times;</span>
            <div>
                <h5>Confirm your visit</h5>
                <Error v-if="error" :error="error"/>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Your doctor:
                        <span>{{ doctor.firstName }} {{ doctor.lastName }}</span></li>
                    <li class="list-group-item">Doctor's contacts:
                        <span>{{ doctor.email }} {{ doctor.phone }}</span></li>
                    <li class="list-group-item">Time of your visit:
                        <span>{{ day }}, {{ startTime }}</span></li>
                </ul>
                <button class="mr-3 mt-3 btn btn-block btn-outline-danger"
                        @click="createVisit">Confirm</button>
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

export default {
    components: { Error},

    computed: {
        ...mapGetters(['user'])
    },

    data() {
        return {
            error: ''
        }
    },

    props: {
        isOpen: {
            required: true
        },
        doctor: {
            required: true
        },
        day: {
            required: true
        },
        startTime: {
            required: true
        }
    },

    methods: {
        async createVisit() {
            const data = {
                doctorId: this.doctor.id,
                day: this.day,
                startTime: this.startTime,
            };
            try {
                const response = await axios.post(`${SERVER}/visit/create`, data, {
                    headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }
            });
                if (response.status === 201) {
                    console.log(true);
                    await router.push('/patient');
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
