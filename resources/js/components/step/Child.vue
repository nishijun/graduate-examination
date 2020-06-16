<template>
  <main class="l-main1 p-step">

    <!-- 上部メニュー -->
    <div class="p-step__menu">
      <routerLink tag="a" :to="{name: 'step', params: {stepId: stepId}}" class="c-link"><i class="fas fa-chevron-left u-margin__10r"></i>STEP詳細に戻る</routerLink>
      <button class="c-btn-neo" v-if="!isClear" @click="clear">クリア</button>
      <button class="c-btn-orange" v-else @click="unclear">クリア解除</button>
    </div>

    <!-- 子STEP情報 -->
    <img :src="'/storage/kid_thumbnails/' + kid.thumbnail" alt="STEPイメージ" class="p-step__image" v-if="kid.thumbnail">
    <img src="/img/noimg.png" alt="STEPイメージ" class="p-step__image" v-else>
    <div class="c-title">
      <div class="c-title-content u-margin__30t">STEP {{ kid.order }}</div>
    </div>
    <p class="p-step__title">{{ kid.title }}</p>
    <p class="p-step__achievement_time">目安達成時間：{{ kid.achievement_time }}</p>
    <div class="p-step__content">{{ kid.content }}</div>

  </main>
</template>

<script>
export default {
  data () {
    return {
      kid: {
        order: '',
        title: '',
        achievement_time: '',
        content: '',
        thumbnail: ''
      },
      stepId: '',
      isClear: null
    }
  },
  methods: {
    // 子STEP情報取得
    async getKid() {
      const response = await axios.get(`/api/step/${this.$route.params.stepId}/${this.$route.params.childId}`);

      this.stepId = response.data[0];

      // 子STEP情報
      this.kid.order = response.data[1].order;
      this.kid.title = response.data[1].title;
      this.kid.achievement_time = response.data[1].achievement_time;
      this.kid.content = response.data[1].content;
      this.kid.thumbnail = response.data[1].thumbnail;

      // クリア有無
      this.isClear = response.data[2] ? true : false;
    },

    // STEPクリア
    async clear() {
      const formData = new FormData();
      formData.append('user_id', this.$store.state.auth.user.id);
      formData.append('kid_id', this.$route.params.childId);

      const response = await axios.post('/api/step/clear', formData);

      this.isClear = true;
    },

    // STEPクリア解除
    async unclear() {
      if (confirm('クリア解除しますか？')) {
        const formData = new FormData();
        formData.append('user_id', this.$store.state.auth.user.id);
        formData.append('kid_id', this.$route.params.childId);

        const response = await axios.post('/api/step/unclear', formData);

        this.isClear = false;
      }
    },
  },
  created() {
    this.getKid();
  }
}
</script>
