<template>
  <main class="l-main1 p-auth">
    <div class="p-auth__box">
      <h1 class="c-auth__title">パスワード再設定</h1>
        <form @submit.prevent="passwordReminder">

          <!-- エラーメッセージ -->
          <ul v-if="error" class="c-error">
            <li class="c-error-msg">{{ error }}</li>
          </ul>

          <!-- メールアドレス -->
          <div class="u-margin__20b" v-if="isAuth === 0">
            <label for="password-email" class="c-auth__label">メールアドレス</label>
            <input type="email" name="email" class="c-auth__input" id="password-email" v-model="passwordReminderForm.email">
          </div>

          <!-- 認証番号 -->
          <div class="u-margin__20b" v-else-if="isAuth === 1">
            <label for="password-auth" class="c-auth__label">認証番号</label>
            <p>メールアドレス宛にお送りした認証番号を入力して下さい</p>
            <input type="text" name="auth" class="c-auth__input" id="password-auth" v-model="passwordReminderForm.auth">
          </div>

          <!-- 新パスワード -->
          <div class="u-margin__20b" v-else>
            <label for="password-password" class="c-auth__label">新しいパスワード</label>
            <input type="password" name="password" class="c-auth__input u-margin__20b" id="password-password" v-model="passwordReminderForm.password">
            <label for="password-password_re" class="c-auth__label">新しいパスワード再入力</label>
            <input type="password" name="password_re" class="c-auth__input" id="password-password_re" v-model="passwordReminderForm.password_re">
          </div>

          <!-- ボタン -->
          <button class="c-btn-neo u-margin__lauto" type="submit">送信</button>

        </form>
    </div>
  </main>
</template>

<script>
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../../util';

export default {
  data() {
    return {
      passwordReminderForm: {
        email: '',
        auth: '',
        password: '',
        password_re: ''
      },
      isAuth: 0,
      error: null
    };
  },
  methods: {
    // パスワードリセット処理
    async passwordReminder() {
      const formData = new FormData();
      formData.append('isAuth', this.isAuth);

      switch (this.isAuth) {

        // メールアドレス認証＆認証番号送付
        case 0:
          if (!this.passwordReminderForm.email) {
            this.error = 'メールアドレスは必須です。';
            return false;
          }

          formData.append('email', this.passwordReminderForm.email);
          const response_0 = await axios.post('/api/password/reminder', formData);

          if (!response_0.data) {
            this.error = '入力されたメールアドレスでの登録情報が見つかりませんでした。';
            return false;
          } else {
            this.error = '';
            this.isAuth = 1;
            break;
          }

        // 認証番号確認
        case 1:
          if (!this.passwordReminderForm.auth) {
            this.error = '認証番号は必須です。';
            return false;
          }

          formData.append('auth', this.passwordReminderForm.auth);
          const response_1 = await axios.post('/api/password/reminder', formData);

          if (!response_1.data) {
            this.error = '認証番号が一致しませんでした。';
            return false;
          } else {
            this.error = '';
            this.isAuth = 2;
            break;
          }

        // 新パスワード設定
        case 2:
          if (this.passwordReminderForm.password !== this.passwordReminderForm.password_re) {
            this.error = '入力したパスワード同士が一致しません。';
            return false;
          } else if (!this.passwordReminderForm.password || !this.passwordReminderForm.password_re) {
            this.error = 'パスワードは入力必須です。';
            return false;
          } else {
            formData.append('email', this.passwordReminderForm.email);
            formData.append('password', this.passwordReminderForm.password);

            const response_2 = await axios.post('/api/password/reminder', formData);

            // サクセスメッセージ登録
            this.$store.commit('message/setContent', {
              content: 'パスワードが変更されました！',
              timeout: 6000
            });

            this.$router.push('/login');
            break;
          }
      }
    }
  }
}
</script>
