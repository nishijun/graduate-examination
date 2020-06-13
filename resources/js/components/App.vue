<template>
  <div>

    <!-- ヘッダー -->
    <Header/>

    <!-- サクセスメッセージ -->
    <Message></Message>

    <!-- メインコンテンツ（ページ遷移時アニメーション） -->
    <transition name="page" mode="out-in">
      <routerView></routerView>
    </transition>

    <!-- フッター -->
    <Footer/>

  </div>
</template>

<script>
import { INTERNAL_SERVER_ERROR } from '../util';
import Header from './requirement/Header.vue';
import Footer from './requirement/Footer.vue';
import Message from './Message.vue';

export default {
  components: {
    Header,
    Footer,
    Message
  },
  computed: {
    errorCode () {
      return this.$store.state.error.code;
    }
  },
  watch: {

    // 500エラー発生時、エラー画面へ遷移
    errorCode: {
      handler (val) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push('/500');
        }
      },
      immediate: true
    },

    // APIレスポンスコードリセット
    $route () {
      this.$store.commit('error/setCode', null);
    }
    
  }
}
</script>
