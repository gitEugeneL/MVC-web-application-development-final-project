import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'


const state = {
    user: null,
    role: null
};


const store = new Vuex.Store({
    state,
    plugins: [ createPersistedState() ],

    getters: {
        user: (state) => {
            return state.user;
        },
        role: (state) => {
            return state.role;
        }
    },

    actions: {
        user(context, user) {
            context.commit('user', user);
        },
        role(context, role) {
            context.commit('role', role)
        }
    },

    mutations: {
        user(state, user) {
            state.user = user;
        },
        role(state, role) {
            state.role = role;
        }
    }
});

export default store;