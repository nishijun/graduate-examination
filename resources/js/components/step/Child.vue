<template>
  <main class="l-main1 p-step">

    <!-- 上部メニュー -->
    <div class="p-step__menu">
      <routerLink tag="a" :to="{name: 'step', params: {stepId: stepId}}" class="c-link"><i class="fas fa-chevron-left u-margin__10r"></i>STEP詳細に戻る</routerLink>
      <button class="c-btn-neo" v-if="!isClear && !isOwn && previousClear" @click="clear">クリア</button>
      <button class="c-btn-orange" v-else-if="isClear && !isOwn && previousClear" @click="unclear">クリア解除</button>
      <button class="c-btn-green u-margin__lauto" v-else-if="isOwn"><routerLink tag="a" :to="{name: 'editStep'}">編集する</routerLink></button>
      <p v-if="!isOwn && !previousClear">まだ前STEPをクリアしていません。</p>
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

    <!-- 前後子STEPリンク -->
    <div class="p-step__link">
      <routerLink class="p-step__link-left" tag="a" :to="{name: 'step-child', params: {stepId: stepId, childId: previousStep}}" v-if="previousStep"><i class="fas fa-chevron-left u-margin__10r"></i>前STEP</routerLink>
      <routerLink class="p-step__link-right" tag="a" :to="{name: 'step-child', params: {stepId: stepId, childId: nextStep}}" v-if="nextStep">次STEP<i class="fas fa-chevron-right u-margin__10l"></i></routerLink>
      <div class="p-step__link-cb"></div>
    </div>

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
      isClear: null,
      isOwn: false,
      isChallenge: true,
      previousStep: '',
      nextStep: '',
      previousClear: ''
    }
  },
  methods: {
    // 子STEP情報取得
    async getKid(id) {
      const response = await axios.get(`/api/step/${this.$route.params.stepId}/${id}`);

      // STEP作成者本人またはSTEP未挑戦者は閲覧不可
      if (this.$store.state.auth.user.id === response.data[0].user_id) {
        this.isOwn = true;
      } else if (!response.data[3]) {
        this.isChallenge = false;
        this.$router.push(`/steps/${this.$route.params.stepId}`);
      }

      this.stepId = response.data[0].id;

      // 子STEP情報
      this.kid.order = response.data[1].order;
      this.kid.title = response.data[1].title;
      this.kid.achievement_time = response.data[1].achievement_time;
      this.kid.content = response.data[1].content;
      this.kid.thumbnail = response.data[1].thumbnail;

      // クリア有無
      this.isClear = response.data[2] ? true : false;

      // 前後子STEP情報
      this.previousStep = response.data[4] ? response.data[4].id : null;
      this.nextStep = response.data[5] ? response.data[5].id : null;

      // 前STEPクリア状況
      this.previousClear = response.data[6] ? true : false;
    },

    // STEPクリア
    async clear() {
      // STEP未挑戦者はクリア不可
      if (this.isChallenge) {
        const formData = new FormData();
        formData.append('user_id', this.$store.state.auth.user.id);
        formData.append('kid_id', this.$route.params.childId);

        const response = await axios.post('/api/step/clear', formData);
        this.isClear = true;
      }
    },

    // STEPクリア解除
    async unclear() {
      if (confirm('クリア解除しますか？（このSTEP以降のクリアも解除されます）')) {
        const formData = new FormData();
        formData.append('kid_id', this.$route.params.childId);
        formData.append('step_id', this.stepId);

        const response = await axios.post('/api/step/unclear', formData);

        console.log(response.data);
        this.isClear = false;
      }
    },
  },
  beforeRouteUpdate(to, from, next) {
    this.getKid(to.params.childId);
    next();
  },
  created() {
    this.getKid(this.$route.params.childId);
  }
}
</script>
