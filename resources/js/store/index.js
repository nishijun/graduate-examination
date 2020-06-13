import Vue from 'vue';
import Vuex from 'vuex';

import auth from './auth';
import step from './step';
import error from './error';
import message from './message';

Vue.use(Vuex);

const store = new Vuex.Store({
  modules: {
    auth,
    step,
    error,
    message
  }
});

export default store;
