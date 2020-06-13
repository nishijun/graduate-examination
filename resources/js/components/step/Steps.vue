<template>
  <main class="l-main1 p-steps">
    <p class="p-steps__hit">HIT {{ filteredSteps.length }}件</p>
    <div class="p-steps__contents">

      <!-- 検索フォーム -->
      <div class="p-auth__box p-steps__contents-search">
        <h1 class="c-auth__title">STEP検索</h1>
        <div class="u-margin__20b">

          <!-- カテゴリ -->
          <label for="search-category" class="c-auth__label">カテゴリ</label>
          <select id="search-category" class="c-auth__input" v-model="searchForm.category_id">
            <option value="">選択して下さい</option>
            <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
          </select>
        </div>

        <!-- 目安達成時間 -->
        <div class="u-margin__20b">
          <label for="search-time" class="c-auth__label">目安達成時間</label>
          <input id="search-time" type="number" class="c-auth__input" v-model="searchForm.achievement_time">
        </div>
      </div>

      <!-- 検索結果 -->
      <div class="p-steps__contents-results" v-if="filteredSteps.length >= 1">

        <routerLink v-for="step in filteredSteps" class="p-steps__contents-result" :to="{name: 'step', params: {stepId: step.id}}" tag="a" :key="step.id">
          <img :src="'/storage/step_thumbnails/' + step.thumbnail" alt="STEPイメージ" class="c-steps__image" v-if="step.thumbnail">
          <img src="/img/noimg.png" alt="STEPイメージ" class="c-steps__image" v-else>
          <span class="c-steps__category">{{ step.category.name }}</span>
          <span class="c-steps__time">達成目安：{{ step.achievement_time }}h</span>
          <p class="c-steps__name">{{ step.title }}</p>
        </routerLink>

      </div>
      <p class="c-nodata u-width__71" v-else>データがありません</p>

    </div>
  </main>
</template>

<script>
export default {
  data() {
    return {
      searchForm: {
        category_id: '',
        achievement_time: ''
      },
      steps: this.$store.state.step.steps
    }
  },
  computed: {
    categories() {
      return this.$store.state.step.categories;
    },
    filteredSteps() {
      if (!this.searchForm.category_id && !this.searchForm.achievement_time) {
        return this.steps;
      } else {
        if (this.searchForm.category_id && this.searchForm.achievement_time) {
          return this.steps.filter(item => {
            return item.category_id === this.searchForm.category_id && item.achievement_time <= this.searchForm.achievement_time;
          });
        } else if (!this.searchForm.category_id) {
          return this.steps.filter(item => {
            return item.achievement_time <= this.searchForm.achievement_time;
          });
        } else {
          return this.steps.filter(item => {
            return item.category_id === this.searchForm.category_id;
          });
        }
      }
    }
  }
}
</script>
