import Vue from 'vue';
import VueRouter from 'vue-router';
import { setTitle } from './mixins/pageTitle';
import store from './store';

import Top from './components/Top.vue';
import Login from './components/auth/Login.vue';
import Signup from './components/auth/Signup.vue';
import PasswordReminder from './components/auth/PasswordReminder.vue';
import Steps from './components/step/Steps.vue';
import Step from './components/step/Step.vue';
import Child from './components/step/Child.vue';
import MyPageManager from './components/MyPageManager.vue';
import MyPage from './components/mypage/MyPage.vue';
import CreateStep from './components/mypage/CreateStep.vue';
import EditStep from './components/mypage/EditStep.vue';
import EditProfile from './components/mypage/EditProfile.vue';
import Withdrawal from './components/mypage/Withdrawal.vue';
import System from './components/error/System.vue';

Vue.use(VueRouter);

export const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'home',
      component: Top,
      beforeEnter (to, from, next) {
        if (store.getters['auth/check']) {
          next('/steps');
        } else {
          next();
        }
      }
    },
    {
      path: '/login',
      name: 'login',
      component: Login,
      meta: {
        title: 'ログイン'
      },
      beforeEnter (to, from, next) {
        if (store.getters['auth/check']) {
          next('/steps');
        } else {
          next();
        }
      }
    },
    {
      path: '/signup',
      name: 'signup',
      component: Signup,
      meta: {
        title: 'ユーザ登録'
      },
      beforeEnter (to, from, next) {
        if (store.getters['auth/check']) {
          next('/steps');
        } else {
          next();
        }
      }
    },
    {
      path: '/password',
      name: 'password',
      component: PasswordReminder,
      meta: {
        title: 'パスワードリマインダ'
      },
      beforeEnter (to, from, next) {
        if (store.getters['auth/check']) {
          next('/steps');
        } else {
          next();
        }
      }
    },
    {
      path: '/steps',
      name: 'steps',
      component: Steps,
      meta: {
        title: 'STEP一覧'
      },
      beforeEnter (to, from, next) {
        if (!store.getters['auth/check']) {
          next('/');
        } else {
          next();
        }
      }
    },
    {
      path: '/steps/:stepId(\\d+)',
      name: 'step',
      component: Step,
      meta: {
        title: '親STEP詳細'
      },
      beforeEnter (to, from, next) {
        if (!store.getters['auth/check']) {
          next('/');
        } else {
          next();
        }
      }
    },
    {
      path: '/steps/:stepId/:childId',
      name: 'step-child',
      component: Child,
      meta: {
        title: '子STEP詳細'
      },
      beforeEnter (to, from, next) {
        if (!store.getters['auth/check']) {
          next('/');
        } else {
          next();
        }
      }
    },

    // マイページ
    {
      path: '/mypage',
      component: MyPageManager,
      children: [
        {
          path: '',
          name: 'mypage',
          component: MyPage,
          meta: {
            title: 'マイページ'
          },
          beforeEnter (to, from, next) {
            if (!store.getters['auth/check']) {
              next('/');
            } else {
              next();
            }
          }
        },
        {
          path: 'createStep',
          name: 'createStep',
          component: CreateStep,
          meta: {
            title: 'STEP登録'
          },
          beforeEnter (to, from, next) {
            if (!store.getters['auth/check']) {
              next('/');
            } else {
              next();
            }
          }
        },
        {
          path: 'editStep/:stepId',
          name: 'editStep',
          component: EditStep,
          meta: {
            title: 'STEP編集'
          },
          beforeEnter (to, from, next) {
            if (!store.getters['auth/check']) {
              next('/');
            } else {
              next();
            }
          }
        },
        {
          path: 'editProfile',
          name: 'editProfile',
          component: EditProfile,
          meta: {
            title: 'プロフィール編集'
          },
          beforeEnter (to, from, next) {
            if (!store.getters['auth/check']) {
              next('/');
            } else {
              next();
            }
          }
        },
        {
          path: 'withdrawal',
          name: 'withdrawal',
          component: Withdrawal,
          meta: {
            title: '退会'
          },
          beforeEnter (to, from, next) {
            if (!store.getters['auth/check']) {
              next('/');
            } else {
              next();
            }
          }
        },
      ]
    },
    {
      path: '/500',
      name: 'system',
      component: System
    }
  ]
});

router.beforeEach((to, from, next) => {
  setTitle(to.meta.title);
  next();
});
