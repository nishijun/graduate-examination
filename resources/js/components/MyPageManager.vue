<template>
  <main class="l-main1 p-mypage">

    <!-- SP時サイドメニュー -->
    <div class="p-mypage__toggle u-display__lg-show" @click="toggle = !toggle">
      <p class="p-mypage__toggle-item p-mypage__toggle-display">
        <span>{{ meta }}</span>
        <i class="fas fa-chevron-down u-margin__10l p-mypage__toggle-item-icon"></i>
      </p>
      <ul v-if="toggle" class="p-mypage__toggle-list">
        <li class="p-mypage__toggle-item p-mypage__toggle-list-link">
          <routerLink tag="a" :to="{name: 'mypage'}">マイページ</routerLink>
        </li>
        <li class="p-mypage__toggle-item p-mypage__toggle-list-link">
          <routerLink tag="a" :to="{name: 'createStep'}">STEP登録</routerLink>
        </li>
        <li class="p-mypage__toggle-item p-mypage__toggle-list-link">
          <routerLink tag="a" :to="{name: 'editProfile'}">プロフィール編集</routerLink>
        </li>
        <li class="p-mypage__toggle-item p-mypage__toggle-list-link">
          <routerLink tag="a" :to="{name: 'withdrawal'}">退会</routerLink>
        </li>
        <li class="p-mypage__toggle-item p-mypage__toggle-list-link">
          <routerLink tag="a" :to="{name: 'steps'}">STEP一覧に戻る</routerLink>
        </li>
      </ul>
    </div>

    <!-- マイページのメインコンテンツ（画面遷移時アニメーション） -->
    <transition name="page" mode="out-in">
      <routerView></routerView>
    </transition>

    <!-- PC時サイドメニュー -->
    <ul class="p-mypage__menu u-display__lg-delete">
      <li class="p-mypage__menu-item">
        <routerLink tag="a" :to="{name: 'mypage'}">マイページ</routerLink>
      </li>
      <li class="p-mypage__menu-item">
        <routerLink tag="a" :to="{name: 'createStep'}">STEP登録</routerLink>
      </li>
      <li class="p-mypage__menu-item">
        <routerLink tag="a" :to="{name: 'editProfile'}">プロフィール編集</routerLink>
      </li>
      <li class="p-mypage__menu-item">
        <routerLink tag="a" :to="{name: 'withdrawal'}">退会</routerLink>
      </li>
      <li class="p-mypage__menu-item">
        <routerLink tag="a" :to="{name: 'steps'}">STEP一覧に戻る</routerLink>
      </li>
    </ul>

  </main>
</template>

<script>
export default {
  data() {
    return {
      toggle: false
    };
  },
  computed: {
    meta() {
      return this.$route.meta.title;
    }
  }
}
</script>
