<template>
    <div v-if="isOpen" class="modal">
        <div class="modal-content">
            <span class="close" @click="closeModal">&times;</span>
                <form @submit.prevent="handleSubmit">
                    <h5>Add new doctor</h5>
                    <Error v-if="error" :error="error"/>
                    <div class="row">
                        <div class="col-6 form-group">
                            <label>First name</label>
                            <input v-model="firstName"
                                   type="text"
                                   class="form-control"
                                   placeholder="first name">
                        </div>
                        <div class="col-6 form-group">
                            <label>Last name</label>
                            <input v-model="lastName"
                                   type="text"
                                   class="form-control"
                                   placeholder="last name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 form-group">
                            <label>Phone</label>
                            <input v-model="phone"
                                   type="text"
                                   class="form-control"
                                   placeholder="phone">
                        </div>
                        <div class="col-6 form-group">
                            <label>Email</label>
                            <input v-model="email"
                                   type="text"
                                   class="form-control"
                                   placeholder="email">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 form-group">
                            <label>Password</label>
                            <input v-model="password"
                                   type="password"
                                   class="form-control"
                                   placeholder="password">
                        </div>
                        <div class="col-6 form-group">
                            <label>Confirm password</label>
                            <input v-model="confirmPassword"
                                   type="text"
                                   class="form-control"
                                   placeholder="confirm password">
                        </div>
                    </div>
                    <div class="form-group">
                        <h6 class="mb-3">Specialization</h6>

                        <label class="mr-4" v-for="specialization in specializations" :key="specialization.id" >
                            <input
                                    class="checkbox"
                                    type="checkbox"
                                    :value="specialization.id"
                                    v-model="selectedSpecializations"
                            />
                            {{ specialization.name }}
                        </label>
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
    components: { Error },

    data() {
        return {
            firstName: '',
            lastName: '',
            phone: '',
            email: '',
            password: '',
            confirmPassword: '',
            selectedSpecializations: [],
            error: ''
        }
    },

    props: {
        isOpen: {
            type: Boolean,
            required: true
        },
        specializations: {
            type: Array,
            required: true
        }
    },

    methods: {
        async handleSubmit() {
            if (this.password !== this.confirmPassword) {
                this.error = 'Passwords don\'t match';
                return;
            }
            this.error = '';

            const data = {
                firstName: this.firstName,
                lastName: this.lastName,
                phone: this.phone,
                email: this.email,
                password: this.password,
                specializationsId: this.selectedSpecializations
            };

            try {
                await axios.post(`${SERVER}/doctor/create`, data, {
                    headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }
                });
                this.closeModal();
            } catch (e){
                this.error = e.response.data.detail;
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
    max-width: 600px;
    transition: all .3s;
}

h5, h6 {
    text-align: center;
}

.form-group {
    text-align: left;
}

.close {
    position: absolute;
    top: 10px;
    right: 20px;
    cursor: pointer;
}

.checkbox {
    transform: scale(2);
    margin-right: 11px;
}
</style>
