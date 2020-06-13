<template>
  <main class="l-main1 p-auth">
    <div class="p-auth__box">
      <h1 class="c-auth__title">ユーザ登録</h1>
      <form @submit.prevent="signup">

        <!-- エラーメッセージ -->
        <ul v-if="signupErrors" class="c-error">
          <li v-for="msg in signupErrors.name" :key="msg" class="c-error-msg">{{ msg }}</li>
          <li v-for="msg in signupErrors.email" :key="msg" class="c-error-msg">{{ msg }}</li>
          <li v-for="msg in signupErrors.password" :key="msg" class="c-error-msg">{{ msg }}</li>
        </ul>

        <!-- 名前 -->
        <div class="u-margin__20b">
          <label class="c-auth__label" for="signup-name">名前</label>
          <input id="signup-name" type="text" class="c-auth__input" v-model="signupForm.name">
        </div>

        <!-- メールアドレス -->
        <div class="u-margin__20b">
          <label class="c-auth__label" for="signup-email">メールアドレス</label>
          <input type="email" class="c-auth__input" v-model="signupForm.email" id="signup-email">
        </div>

        <!-- パスワード -->
        <div class="u-margin__20b">
          <label class="c-auth__label" for="signup-password">パスワード</label>
          <input type="password" class="c-auth__input" v-model="signupForm.password" id="signup-password">
        </div>

        <!-- パスワード再入力 -->
        <div class="u-margin__20b">
          <label class="c-auth__label" for="signup-password_confirmation">パスワード再入力</label>
          <input type="password" class="c-auth__input" v-model="signupForm.password_confirmation" id="signup-password_confirmation">
        </div>

        <!-- ボタン -->
        <button class="c-btn-neo u-margin__lauto" type="submit">登録</button>

      </form>
    </div>
  </main>
</template>

<script>
import { mapState } from 'vuex';

export default {
  data() {
    return {
      signupForm: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      }
    }
  },
  computed: {
    ...mapState({
      signupErrors: state => state.auth.signupErrorMessages,
      apiStatus: state => state.auth.apiStatus
    })
  },
  methods: {
    // ユーザ登録処理
    async signup () {
      await this.$store.dispatch('auth/signup', this.signupForm);
      if (this.apiStatus) {
        this.$router.push('/');
      }
    },
    
    // エラーメッセージリセット
    clearError () {
      this.$store.commit('auth/setSignupErrorMessages', null);
    }
  },
  created() {
    this.clearError();
  }
}
</script>
