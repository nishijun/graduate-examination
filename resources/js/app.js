import Vue from 'vue';

import "core-js/stable";
import "regenerator-runtime/runtime";

import { router } from './router';
import { globalMixins } from './mixins/pageTitle';
import store from './store';
import './bootstrap';
import App from './components/App.vue';

// ページタイトル
Vue.mixin(globalMixins);

const previousFunction = async () => {
  // ログインユーザ情報
  await store.dispatch('auth/currentUser');

  // STEPカテゴリー取得
  await store.dispatch('step/getCategories');

  // 全STEP取得
  await store.dispatch('step/getSteps');

  const app = new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App)
  });
};
previousFunction();
