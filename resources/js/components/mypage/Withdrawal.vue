<template>
  <div class="p-mypage__display">
    <div class="c-title">
      <div class="c-title-content">退会</div>
    </div>
    <form @submit.prevent="withdrawal">
      <p class="c-withdrawal">本当に退会しますか？</p>
      <button class="c-btn-neo u-margin__auto" type="submit">退会</button>
    </form>
  </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
  computed: {
    ...mapState({
      apiStatus: state => state.auth.apiStatus,
    })
  },
  methods: {
    // 退会処理
    async withdrawal() {
      const response = await axios.get('/api/withdrawal');
      console.log(response.data);
      this.logout();
    },
    
    // 退会処理後のログアウト
    async logout() {
      await this.$store.dispatch('auth/logout');
      if (this.apiStatus) {
        this.$router.push('/login');
      }
    }
  }
}
</script>
