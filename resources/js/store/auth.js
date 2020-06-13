import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util';

const state = {
  user: null,
  apiStatus: null,
  loginErrorMessages: null,
  signupErrorMessages: null,
  passwordErrorMessages: null
}

const getters = {
  check: state => !! state.user
}

const mutations = {
  setUser (state, user) {
    state.user = user;
  },
  setApiStatus (state, status) {
    state.apiStatus = status;
  },
  setLoginErrorMessages (state, messages) {
    state.loginErrorMessages = messages;
  },
  setSignupErrorMessages (state, messages) {
    state.signupErrorMessages = messages;
  },
  setPasswordErrorMessages (state, messages) {
    state.passwordErrorMessages = messages;
  }
}

const actions = {
  // 会員登録
  async signup (context, data) {
    context.commit('setApiStatus', null);
    const response = await axios.post('/api/signup', data);

    if (response.status === CREATED) {
      context.commit('setApiStatus', true);
      context.commit('setUser', response.data);
      return false;
    }

    context.commit('setApiStatus', false);
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setSignupErrorMessages', response.data.errors);
    } else {
      context.commit('error/setCode', response.status, { root: true });
    }
  },

  // ログイン
  async login (context, data) {
    context.commit('setApiStatus', null);
    const response = await axios.post('/api/login', data);

    if (response.status === OK) {
      context.commit('setApiStatus', true);
      context.commit('setUser', response.data);
      return false;
    }

    context.commit('setApiStatus', false);
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setLoginErrorMessages', response.data.errors);
    } else {
      context.commit('error/setCode', response.status, { root: true });
    }
  },

  // ログアウト
  async logout (context) {
    context.commit('setApiStatus', null);
    const response = await axios.post('/api/logout');

    if (response.status === OK) {
      context.commit('setApiStatus', true);
      context.commit('setUser', null);
      return false;
    }

    context.commit('setApiStatus', false);
    context.commit('error/setCode', response.status, { root: true });
  },

  // ログインユーザーチェック
  async currentUser (context) {
    context.commit('setApiStatus', null);
    const response = await axios.get('/api/user');
    const user = response.data || null;

    if (response.status === OK) {
      context.commit('setApiStatus', true);
      context.commit('setUser', user);
      return false;
    }

    context.commit('setApiStatus', false);
    context.commit('error/setCode', response.status, { root: true });
  },

  // パスワードリマインダ
  async passwordReminder (context, data) {
    const response = await axios.post('/api/passwordReminder', data);
    console.log(response);
  },

  // プロフィール編集
  async editProfile (context, data) {
    context.commit('setUser', data);
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
