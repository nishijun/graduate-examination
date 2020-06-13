<template>
  <div class="p-mypage__display">

    <!-- 登録済みSTEP -->
    <div class="c-title">
      <div class="c-title-content">登録済みSTEP</div>
    </div>

    <div class="p-mydata__mysteps" v-if="mySteps.length >= 1">

      <div class="p-mydata__mystep" v-for="(myStep, index) in mySteps" :key="index">
        <div v-if="!(isToggle.indexOf(index) >= 0)" @click="toggle(index)">
          <img :src="'/storage/step_thumbnails/' + myStep.thumbnail" alt="STEPイメージ" class="c-steps__image" v-if="myStep.thumbnail">
          <img src="/img/noimg.png" alt="STEPイメージ" class="c-steps__image" v-else>
          <span class="c-steps__category">{{ myStep.category.name }}</span>
          <span class="c-steps__time">達成目安：{{ myStep.achievement_time }}h</span>
          <p class="c-steps__name">{{ myStep.title }}</p>
        </div>
        <div v-else class="c-stepMenu">
          <p @click="toggle(index)"><i class="fas fa-times"></i></p>
          <button class="c-btn u-margin__auto u-margin__20y"><routerLink tag="a" :to="{name: 'editStep', params: {stepId: myStep.id}}">編集する</routerLink></button>
          <button class="c-btn-orange u-margin__auto" @click="deleteStep(myStep.id)">削除する</button>
        </div>
      </div>

    </div>
    <p class="c-nodata" v-else>まだSTEPが登録されていません</p>

    <!-- チャレンジ中のSTEP -->
    <div class="c-title">
      <div class="c-title-content">チャレンジ中のSTEP</div>
    </div>

    <div class="p-mydata__mysteps" v-if="myChallengeSteps.length >= 1">

      <routerLink class="p-mydata__mystep" :to="{name: 'step', params: {stepId: myChallengeStep.id}}" tag="a" v-for="myChallengeStep in myChallengeSteps" :key="myChallengeStep.id">
        <img :src="'/storage/step_thumbnails/' + myChallengeStep.thumbnail" alt="STEPイメージ" class="c-steps__image" v-if="myChallengeStep.thumbnail">
        <img src="/img/noimg.png" alt="STEPイメージ" class="c-steps__image" v-else>
        <span class="c-steps__category">{{ myChallengeStep.category.name }}</span>
        <span class="c-steps__time">達成目安：{{ myChallengeStep.achievement_time }}h</span>
        <div class="c-steps__outerBar">
          <div class="c-steps__innerBar" :style="{'width': Math.round(myChallengeStep.achievementCount / myChallengeStep.kidsCount * 100) + '%' }">
          </div>
          <span class="c-steps__barChar">進捗：{{ Math.round(myChallengeStep.achievementCount / myChallengeStep.kidsCount * 100) }}%</span>
        </div>
        <p class="c-steps__name">{{ myChallengeStep.title }}</p>
      </routerLink>

    </div>
    <p class="c-nodata" v-else>まだ挑戦していません</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      mySteps: [],
      myChallengeSteps: [],
      isToggle: []
    };
  },
  methods: {
    // マイページ情報取得
    async getData() {
      const response = await axios.get('/api/step/mydata');

      this.mySteps = response.data[0];
      this.myChallengeSteps = response.data[1];
    },

    // STEP削除
    async deleteStep(stepId) {
      if (confirm('本当に削除しますか？')) {
        const formData = new FormData();
        formData.append('id', stepId);
        const response = await axios.post('/api/step/delete', formData);
        this.getData();

        // Store更新
        this.$store.dispatch('step/getSteps');
      }
    },

    toggle(index){
      if (this.isToggle.indexOf(index) >= 0) {
        this.isToggle = this.isToggle.filter(n => n !== index)
      } else {
        this.isToggle.push(index)
      }
    }
  },
  created() {
    this.getData();
  }
}
</script>
