<template>
    <div class="container-lg">
        <div class="d-flex flex-wrap justify-content-center" v-if="records">
            <div v-for="record in records" :key="record.id">
                <div @click="record.recordModal = true" class="card card-record">
                    <b class="card-title">{{ record.date }}, {{ record.startTime }}-{{ record.endTime }}</b>
                    <i>{{ record.doctorFirstName }} {{ record.doctorLastName }}</i>
                    <i>{{ record.name }}</i>
                </div>
                <Record :isOpen="record.recordModal" :record="record"
                       @close="record.recordModal = false" />
            </div>
        </div>
    </div>
</template>


<script>
    import axios from "axios";
    import {SERVER} from "@/config";
    import Record from "@/components/patient/Record.vue";

    export default {
        components: {Record},
        data() {
            return {
                records: null,
            }
        },

        created() {
            this.getMedicalRecords();
        },

        methods: {
            async getMedicalRecords() {
                const response = await axios.get(`${SERVER}/record/show-patient`, {
                    headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }
                });
                this.records = response.data;
            },
        }
    }
</script>


<style scoped>
    .card {
        box-shadow: 0 5px 10px rgba(34, 35, 58, .2);
        padding: 10px;
        border-radius: 10px;
        transition: all .3s;
        margin-bottom: 15px;
        margin-right: 10px;
        min-width: 285px;
    }

    .card-record:hover,
    .card-record:focus {
        border: 1px solid #007afd;
        cursor: pointer;
        box-shadow: none;
}
</style>