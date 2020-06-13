import auth from './auth';

const state = {
  categories: null,
  steps: null
}

const getters = {}

const mutations = {
  setCategories (state, categories) {
    state.categories = categories;
  },
  setSteps (state, steps) {
    state.steps = steps;
  }
}

const actions = {
  // STEPカテゴリー取得
  async getCategories (context) {
    const response = await axios.get('/api/categories');
    context.commit('setCategories', response.data);
  },

  // 全STEP情報取得
  async getSteps (context) {
    const response = await axios.get('/api/steps');
    context.commit('setSteps', response.data);
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
