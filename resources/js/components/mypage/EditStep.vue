<template>
  <div class="p-mypage__display">
    <div class="c-title">
      <div class="c-title-content">STEP編集</div>
    </div>

    <!-- エラーメッセージ -->
    <ul v-if="errors" class="c-error">
      <li v-for="error in errors" class="c-error-msg">
        <p v-for="item in error">{{ item }}</p>
      </li>
    </ul>

    <!-- 親／子スイッチ -->
    <div class="c-switch u-margin__20b">
      <div :class="['c-switch-item', {active: step === 'Parent'}]" @click="step = 'Parent'">親STEP</div>
      <div :class="['c-switch-item', {active: step === 'Child'}]" @click="step = 'Child'">子STEP</div>
    </div>

    <form @submit.prevent="editStep" enctype="multipart/form-data">

      <!-- 編集フォーム -->
      <keepAlive>
        <component :is="step" v-bind="{parentData: parentData, childData: childData, categories: categories}" @getStepData="getStepData"></component>
      </keepAlive>

      <!-- ボタン -->
      <button class="c-btn-neo u-margin__lauto" type="submit">編集</button>

    </form>
  </div>
</template>

<script>
import Parent from './elements/Parent.vue';
import Child from './elements/Child.vue';
import { CREATED, UNPROCESSABLE_ENTITY } from '../../util';

export default {
  components: {
    Parent,
    Child
  },
  data() {
    return {
      step: 'Parent',
      parentData: '',
      childData: [],
      categories: [],
      errors: []
    }
  },
  methods: {
    // STEP編集処理
    async editStep () {
      const formData = new FormData();
      if (this.childData.length >= 1) {
        this.childData.forEach((value, index) => {
          Object.entries(value).forEach(([k, v]) => {
            formData.append(k + '[' + index + ']', v);
            if (!this.childData[index].preview) {
              formData.delete('thumbnail' + '[' + index + ']', v);
            }
          });
        });
      }
      Object.entries(this.parentData).forEach(([key, value]) => {
        formData.append('p_' + key, value);
      });

      if (!this.parentData.preview) {
        formData.delete('p_thumbnail');
      }

      const response = await axios.post('/api/step/edit', formData);

      // デバッグ
      console.log(response.data);

      if (response.status === UNPROCESSABLE_ENTITY) {
        this.errors = response.data.errors;
        return false;
      }

      this.reset();

      if (response.status !== CREATED) {
        this.$store.commit('error/setCode', response.status);
        return false;
      }

      // Store更新
      this.$store.dispatch('step/getSteps');

      // サクセスメッセージ登録
      this.$store.commit('message/setContent', {
        content: 'STEPが更新されました！',
        timeout: 6000
      });

      this.$router.push('/mypage');
    },

    // 入力欄の値とプレビュー表示をクリア
    reset () {
      this.parentData.preview = '';
      this.parentData.thumbnail = null;
      this.$el.querySelector('input[type="file"]').value = null;
    },

    // 編集前のSTEP情報取得
    async getStepData() {
      const formData1 = new FormData();
      formData1.append('stepId', this.$route.params.stepId);
      const response = await axios.post('/api/step/getData', formData1);

      response.data[0].preview = null;

      this.parentData = response.data[0];
      this.childData = response.data[1];
      this.categories = this.$store.state.step.categories;

      // 他人のSTEPだった場合、マイページにリダイレクト
      if (this.parentData.user_id !== this.$store.state.auth.user.id) {
        this.$router.push('/mypage');
      }
    }
  },
  created() {
    this.getStepData();
    this.errors = null;
  }
}
</script>
