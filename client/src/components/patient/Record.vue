<template>
    <div v-if="isOpen" class="modal">
        <div class="modal-content">
            <span class="close" @click="closeModal">&times;</span>
            <h4>Medical Record</h4>
            <h5>{{ record.date }}. {{ record.startTime }}-{{ record.endTime }}</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Doctor:
                    <span>{{ record.doctorFirstName }} {{ record.doctorLastName }}</span>
                </li>
                <li class="list-group-item">Record name:
                    <span>{{ record.name }}</span>
                </li>
                <li class="list-group-item">Record:
                    <span>{{ record.description }}</span>
                </li>
            </ul>
            <button @click="print" class="mt-3 mb-3 btn btn-block btn-outline-primary">Print this record</button>
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

    data() {
        return {}
    },

    props: {
        isOpen: {
            required: true
        },
        record: {
            required: true
        },
    },

    methods: {
        print() {
            window.print();
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
    max-width: 400px;
    transition: all .3s;
}

h5, h4 {
    text-align: center;
}

.close {
    position: absolute;
    top: 10px;
    right: 20px;
    cursor: pointer;
}
</style>
