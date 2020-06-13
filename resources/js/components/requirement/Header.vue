<template>
  <header class="l-header p-header u-padding__20x">

    <!-- ロゴ -->
    <routerLink class="p-header__logo" tag="a" :to="{name: 'home'}" v-if="!isLogin">STEP</routerLink>
    <routerLink class="p-header__logo" tag="a" :to="{name: 'steps'}" v-else>STEP</routerLink>

    <!-- メニュー（未ログイン時） -->
    <ul class="p-header__nav" v-if="!isLogin">
      <li class="c-list u-margin__20r">
        <routerLink :to="{name: 'login'}" tag="a">ログイン</routerLink>
      </li>
      <li class="c-list">
        <routerLink :to="{name: 'signup'}" tag="a">ユーザ登録</routerLink>
      </li>
    </ul>

    <!-- メニュー（ログイン時） -->
    <ul class="p-header__nav" v-else>
      <li class="c-list-neo u-margin__20r" @click="logout">
        ログアウト
      </li>
      <li class="c-list">
        <routerLink :to="{name: 'mypage'}" tag="a">マイページ</routerLink>
      </li>
    </ul>

  </header>
</template>

<script>
import { mapState, mapGetters } from 'vuex';

export default {
  computed: {
    ...mapState({
      apiStatus: state => state.auth.apiStatus
    }),
    ...mapGetters({
      isLogin: 'auth/check'
    })
  },
  methods: {
    // ログアウト処理
    async logout () {
      await this.$store.dispatch('auth/logout');
      if (this.apiStatus) {
        this.$router.push('/login');
      }
    }
  }
}
</script>
