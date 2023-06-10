<template>
    <div v-if="isOpen" class="modal">
        <div class="modal-content">
            <span class="close" @click="closeModal">&times;</span>
            <form @submit.prevent="handleSubmit">
                <h5>Add new office</h5>
                <Error v-if="error" :error="error"/>
                <div class="row">
                    <div class="col-6 form-group">
                        <label>Number</label>
                        <input v-model="number"
                               type="number"
                               class="form-control"
                               placeholder="number">
                    </div>
                    <div class="col-6 form-group">
                        <label>Name</label>
                        <input v-model="name"
                               type="text"
                               class="form-control"
                               placeholder="name">
                    </div>
                </div>
                <button class="btn btn-primary btn-block">Create</button>
            </form>
        </div>
    </div>
</template>

<script>
import Error from "@/components/Error.vue";
import axios from "axios";
import {SERVER} from "@/config";

export default {
    components: {Error},

    data() {
        return {
            number: '',
            name: '',
            error: ''
        }
    },

    props: {
        isOpen: {
            type: Boolean,
            required: true
        }
    },
    methods: {
        async handleSubmit() {
            const data = {
                number: this.number,
                name: this.name,
                isAvailable: false
            };
            try {
                const response = await axios.post(`${SERVER}/office/create`, data, {
                    headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }
                });
                if (response.status === 201) {
                    this.number = '';
                    this.name = '';
                    this.closeModal();
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
