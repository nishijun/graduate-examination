<template>
  <main class="l-main1 p-step">

    <!-- STEP作成者情報モーダル（作成者情報クリック時出現） -->
    <div class="c-hover__display" v-if="isHover">
      <i class="far fa-times-circle c-delete" @click="isHover = false"></i>
      <img :src="'/storage/user_thumbnails/' + step.user.thumbnail" alt="ユーザイメージ" v-if="step.user.thumbnail" class="c-hover__display-image">
      <img src="/img/noimg.png" alt="STEPイメージ" class="c-hover__display-image" v-else>
      <p class="c-hover__display-name">{{ step.user.name}}</p>
      <p class="c-hover__display-body" v-if="step.user.body">{{ step.user.body}}</p>
      <p class="c-hover__display-body" v-else>自己紹介がありません</p>
    </div>

    <!-- 上部メニュー -->
    <div class="p-step__menu">
      <routerLink tag="a" :to="{name: 'steps'}" class="c-link"><i class="fas fa-chevron-left u-margin__10r"></i>STEP一覧に戻る</routerLink>
      <a :href="'https://twitter.com/share?text=' + step.title + '&url=http://ec2-3-1-184-214.ap-southeast-1.compute.amazonaws.com/&hashtags=STEP'" class="c-twitter" target="_blank">ツイートする</a>
    </div>

    <!-- 親STEP情報 -->
    <img :src="'/storage/step_thumbnails/' + step.thumbnail" alt="STEPイメージ" class="p-step__image" v-if="step.thumbnail">
    <img src="/img/noimg.png" alt="STEPイメージ" class="p-step__image" v-else>
    <div class="p-step__detail">
      <table class="c-table" border="1">
        <tr>
          <th class="c-table-th">STEP名</th>
          <td class="c-table-td">{{ step.title }}</td>
        </tr>
        <tr>
          <th class="c-table-th">カテゴリ</th>
          <td class="c-table-td">{{ step.category }}</td>
        </tr>
        <tr>
          <th class="c-table-th">目安達成時間</th>
          <td class="c-table-td">{{ step.achievement_time }}h</td>
        </tr>

        <!-- クリック時、STEP作成者情報モーダル出現 -->
        <tr class="c-hover__target" @click="isHover = !isHover">
          <th class="c-table-th c-table-th-neo">
            <div class="c-table-th-neo-icon"><i class="fas fa-hand-pointer"></i></div>
            作成者名
          </th>
          <td class="c-table-td c-table-td-neo">{{ step.user.name }}</td>
        </tr>

      </table>
      <div class="p-step__detail-sentence">{{ step.content }}</div>
    </div>

    <!-- 子STEPメニュー -->
    <div class="c-title">
      <div class="c-title-content">STEP一覧</div>
    </div>
    <button class="c-btn-neo u-margin__lauto" v-if="!isOwnStep && !isChallenge" @click="challenge">挑戦する</button>
    <button class="c-btn-orange u-margin__lauto" v-else-if="!isOwnStep && isChallenge" @click="release">挑戦解除</button>
    <routerLink class="c-btn-green u-margin__lauto" :to="{name: 'editStep'}" v-else>編集する</routerLink>
    <p v-if="!isChallenge && !isOwnStep && kids.length >= 1">子STEP詳細は挑戦開始後に閲覧可能になります。</p>

    <!-- 子STEP一覧 -->
    <div class="p-step__children" v-if="kids.length >= 1">

      <routerLink :class="['p-step__child', {'disabled': !isChallenge && !isOwnStep}]" tag="a" :to="{name: 'step-child', params: {stepId: $route.params.stepId, childId: kid.id}}" v-for="kid in kids" :key="kid.order">
        <img :src="'/storage/kid_thumbnails/' + kid.thumbnail" alt="STEPイメージ" class="p-step__child-image" v-if="kid.thumbnail">
        <img src="/img/noimg.png" alt="STEPイメージ" class="p-step__child-image" v-else>
        <div class="p-step__child-content">
          <p class="p-step__child-content-title">STEP {{ kid.order }}</p>
          <p class="p-step__child-content-sentence">{{ kid.title }}</p>
        </div>
        <span class="p-step__child-clear" v-if="!isOwnStep && kid.isClear">クリア済み</span>
      </routerLink>

    </div>
    <p v-else class="c-nodata">データがありません</p>

  </main>
</template>

<script>
export default {
  data() {
    return {
      step: {
        user: '',
        title: '',
        category: '',
        achievement_time: '',
        content: '',
        thumbnail: ''
      },
      isOwnStep: null,
      isChallenge: false,
      isHover: false,
      kids: []
    }
  },
  methods: {
    // STEP情報取得
    async getStep() {
      const response = await axios.get(`/api/step/${this.$route.params.stepId}`);

      // 親STEP情報
      this.step.user = response.data[0].user;
      this.step.title = response.data[0].title;
      this.step.category = response.data[0].category.name;
      this.step.achievement_time = response.data[0].achievement_time;
      this.step.content = response.data[0].content;
      this.step.thumbnail = response.data[0].thumbnail;
      this.isOwnStep = response.data[0].user_id === this.$store.state.auth.user.id ? true : false;

      // 子STEP情報
      this.kids = response.data[1];

      // 挑戦履歴
      this.isChallenge = response.data[2] ? true : false;

      // STEPクリア状況
      this.kids.forEach(kid => {
        if (response.data[3].length >= 1) {
          response.data[3].forEach(record => {
            if (record.kid_id === kid.id) {
              kid.isClear = true;
            }
          });
        } else {
          kid.isClear = false;
        }
      });
    },

    // STEP挑戦
    async challenge() {
      const formData = new FormData();
      formData.append('user_id', this.$store.state.auth.user.id);
      formData.append('step_id', this.$route.params.stepId);

      const response = await axios.post('/api/step/challenge', formData);

      this.isChallenge = true;
    },

    // STEP挑戦解除
    async release() {
      if (confirm('本当に解除しますか？\n（このSTEPに関するクリア状況等の情報も全て削除されます。）')) {
        const formData = new FormData();
        formData.append('user_id', this.$store.state.auth.user.id);
        formData.append('step_id', this.$route.params.stepId);

        const response = await axios.post('/api/step/release', formData);

        this.isChallenge = false;
        this.kids.forEach(kid => {
          kid.isClear = false;
        });
      }
    }
  },
  created() {
    this.getStep();
  }
}
</script>
