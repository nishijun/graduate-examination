<template>
  <main class="l-main1 p-auth">
    <div class="p-auth__box">
      <h1 class="c-auth__title">ログイン</h1>
      <form @submit.prevent="login">

        <!-- エラーメッセージ -->
        <ul v-if="loginErrors" class="c-error">
          <li v-for="msg in loginErrors.email" :key="msg" class="c-error-msg">{{ msg }}</li>
          <li v-for="msg in loginErrors.password" :key="msg" class="c-error-msg">{{ msg }}</li>
        </ul>

        <!-- メールアドレス -->
        <div class="u-margin__20b">
          <label for="login-email" class="c-auth__label">メールアドレス</label>
          <input id="login-email" type="email" name="email" class="c-auth__input" v-model="loginForm.email">
          <p v-if=""></p>
        </div>

        <!-- パスワード -->
        <div class="u-margin__20b">
          <label for="login-password" class="c-auth__label">パスワード</label>
          <input type="password" name="password" class="c-auth__input" v-model="loginForm.password" id="login-password">
        </div>

        <!-- ボタン -->
        <button class="c-btn-neo u-margin__lauto" type="submit">ログイン</button>

      </form>

      <p class="u-margin__20t">パスワードを忘れた方は<routerLink :to="{name: 'password'}" tag="a">こちら</routerLink></p>
    </div>
  </main>
</template>

<script>
import { mapState } from 'vuex';

export default {
  data() {
    return {
      loginForm: {
        email: '',
        password: ''
      }
    };
  },
  computed: {
    ...mapState({
      apiStatus: state => state.auth.apiStatus,
      loginErrors: state => state.auth.loginErrorMessages
    })
  },
  methods: {
    // ログイン処理
    async login () {
      await this.$store.dispatch('auth/login', this.loginForm);
      if (this.apiStatus) {
        this.$router.push('/');
      }
    },

    // エラーメッセージリセット
    clearError () {
      this.$store.commit('auth/setLoginErrorMessages', null);
    }
  },
  created() {
    this.clearError();
  }
}
</script>
