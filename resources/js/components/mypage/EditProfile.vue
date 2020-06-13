<template>
  <div class="p-mypage__display">
    <div class="c-title">
      <div class="c-title-content">プロフィール編集</div>
    </div>
    <form enctype="multipart/form-data" @submit.prevent="editProfile">

      <!-- エラーメッセージ -->
      <ul v-if="errors" class="c-error">
        <li v-for="error in errors.name" class="c-error-msg">{{ error }}</li>
        <li v-for="error in errors.email" class="c-error-msg">{{ error }}</li>
        <li v-for="error in errors.thumbnail" class="c-error-msg">{{ error }}</li>
      </ul>

      <!-- 名前 -->
      <div class="u-margin__20b">
        <label for="edit-profile-name" class="c-auth__label">名前</label>
        <input id="edit-profile-name" type="text" name="name" class="c-auth__input" v-model="editProfileForm.name">
      </div>

      <!-- メールアドレス -->
      <div class="u-margin__20b">
        <label for="edit-profile-email" class="c-auth__label">メールアドレス</label>
        <input id="edit-profile-email" type="email" name="email" class="c-auth__input" v-model="editProfileForm.email">
      </div>

      <!-- 自己紹介 -->
      <div class="u-margin__20b">
        <label for="edit-profile-body" class="c-auth__label">自己紹介</label>
        <textarea id="edit-profile-body" name="body" class="c-auth__textarea" v-model="editProfileForm.body"></textarea>
      </div>

      <!-- サムネイル -->
      <div class="u-margin__20b">
        <label for="edit-profile-thumbnail" class="c-auth__label">サムネイル</label>
        <input id="edit-profile-thumbnail" class="c-margin__20t" type="file" name="thumbnail" @change="handleFile"><br>
        <output v-if="preview">
          <img :src="preview" class="c-preview">
        </output>
        <img :src="'/storage/user_thumbnails/' + thumbnail" class="c-preview" v-else-if="!preview && thumbnail">
      </div>

      <!-- ボタン -->
      <button class="c-btn-neo u-margin__lauto" type="submit">編集</button>

    </form>
  </div>
</template>

<script>
import { CREATED, UNPROCESSABLE_ENTITY } from '../../util';

export default {
  data() {
    return {
      editProfileForm: {
        name: this.$store.state.auth.user.name,
        email: this.$store.state.auth.user.email,
        body: this.$store.state.auth.user.body
      },
      thumbnail: this.$store.state.auth.user.thumbnail,
      preview: null,
      errors: null
    }
  },
  methods: {
    // プロフィール編集処理
    async editProfile () {
      const formData = new FormData();
      formData.append('name', this.editProfileForm.name);
      formData.append('email', this.editProfileForm.email);
      formData.append('body', this.editProfileForm.body);
      if (this.preview !== null) {
        formData.append('thumbnail', this.thumbnail);
      }

      // DB更新
      const response = await axios.post('/api/user/edit', formData);

      if (response.status === UNPROCESSABLE_ENTITY) {
        this.errors = response.data.errors;
        return false;
      }

      this.reset();

      if (response.status !== CREATED) {
        this.$store.commit('error/setCode', response.status);
        return false;
      }

      // Vuexのユーザ情報更新
      this.$store.dispatch('auth/editProfile', response.data);

      // サクセスメッセージ登録
      this.$store.commit('message/setContent', {
        content: 'プロフィールが更新されました！',
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
  }
}
</script>
