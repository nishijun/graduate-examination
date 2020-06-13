<template>
  <div class="p-mypage__display">
    <div class="c-title">
      <div class="c-title-content">STEP登録</div>
    </div>
    <form @submit.prevent="createStep" enctype="multipart/form-data">

      <!-- エラーメッセージ -->
      <ul v-if="errors" class="c-error">
        <li v-for="error in errors.title" class="c-error-msg">{{ error }}</li>
        <li v-for="error in errors.category_id" class="c-error-msg">{{ error }}</li>
        <li v-for="error in errors.achievement_time" class="c-error-msg">{{ error }}</li>
        <li v-for="error in errors.content" class="c-error-msg">{{ error }}</li>
        <li v-for="error in errors.thumbnail" class="c-error-msg">{{ error }}</li>
      </ul>

      <!-- STEP名 -->
      <div class="u-margin__20b">
        <label for="create-step-title" class="c-auth__label">STEP名</label>
        <input id="create-step-title" type="text" name="title" class="c-auth__input" v-model="createStepForm.title">
      </div>

      <!-- カテゴリ -->
      <div class="u-margin__20b">
        <label for="create-step-category" class="c-auth__label">カテゴリ</label>
        <select class="c-auth__input" name="category_id" v-model="createStepForm.category_id" id="create-step-category">
          <option value="">選択して下さい</option>
          <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
        </select>
      </div>

      <!-- 目安達成時間 -->
      <div class="u-margin__20b">
        <label for="create-step-time" class="c-auth__label">目安達成時間（h）</label>
        <input id="create-step-time" type="number" name="achievement_time" class="c-auth__input" v-model="createStepForm.achievement_time">
      </div>

      <!-- 内容 -->
      <div class="u-margin__20b">
        <label for="create-step-content" class="c-auth__label">内容</label>
        <textarea id="create-step-content" name="content" class="c-auth__textarea" v-model="createStepForm.content"></textarea>
      </div>

      <!-- サムネイル -->
      <div class="u-margin__20b">
        <label for="create-step-thumbnail" class="c-auth__label">サムネイル</label>
        <input id="create-step-thumbnail" class="c-margin__20t" type="file" name="thumbnail" @change="handleFile" accept="image/png,image/jpeg,image/gif"><br>
        <output v-if="preview">
          <img :src="preview" class="c-preview">
        </output>
      </div>

      <!-- ボタン -->
      <button class="c-btn-neo u-margin__lauto">登録</button>

    </form>
  </div>
</template>

<script>
import { CREATED, UNPROCESSABLE_ENTITY } from '../../util';

export default {
  data() {
    return {
      createStepForm: {
        title: '',
        category_id: '',
        achievement_time: 0,
        content: '',
      },
      thumbnail: null,
      preview: null,
      errors: null
    }
  },
  computed: {
    categories() {
      return this.$store.state.step.categories;
    }
  },
  methods: {
    // STEP追加処理
    async createStep () {
      const formData = new FormData();
      formData.append('title', this.createStepForm.title);
      formData.append('category_id', this.createStepForm.category_id);
      formData.append('achievement_time', this.createStepForm.achievement_time);
      formData.append('content', this.createStepForm.content);
      if (this.thumbnail !== null) {
        formData.append('thumbnail', this.thumbnail);
        console.log(this.thumbnail);
      }

      const response = await axios.post('/api/step/create', formData);

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
        content: 'STEPが登録されました！',
        timeout: 6000
      });

      this.$router.push('/mypage');
    },

    // イメージビュー
    handleFile (event) {
      // 何も選択されていなかったら処理中断
      if (event.target.files.length === 0) {
        this.reset();
        return false;
      }

      // ファイルが画像ではなかったら処理中断
      if (!event.target.files[0].type.match('image.*')) {
        this.reset();
        return false;
      }

      // FileReaderクラスのインスタンスを取得
      const reader = new FileReader();

      // ファイルを読み込み終わったタイミングで実行する処理
      reader.onload = e => {
        // previewに読み込み結果（データURL）を代入する
        // previewに値が入ると<output>につけたv-ifがtrueと判定される
        // また<output>内部の<img>のsrc属性はpreviewの値を参照しているので
        // 結果として画像が表示される
        this.preview = e.target.result;
      }

      // ファイルを読み込む
      reader.readAsDataURL(event.target.files[0]);

  		this.thumbnail = event.target.files[0];
  	},

     // 入力欄の値とプレビュー表示をクリア
    reset () {
      this.preview = '';
      this.thumbnail = null;
      this.$el.querySelector('input[type="file"]').value = null;
    }
  },
  created() {
    this.errors = null;
  }
}
</script>
