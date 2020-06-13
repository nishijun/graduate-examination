<template>
  <div>

    <!-- STEP名 -->
    <div class="u-margin__20b">
      <label for="parent-title" class="c-auth__label">STEP名</label>
      <input id="parent-title" type="text" name="parent-title" class="c-auth__input" v-model="parentData.title">
    </div>

    <!-- カテゴリ -->
    <div class="u-margin__20b">
      <label for="parent-category" class="c-auth__label">カテゴリ</label>
      <select id="parent-category" class="c-auth__input" name="parent-category_id" v-model="parentData.category_id">
        <option value="">選択して下さい</option>
        <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
      </select>
    </div>

    <!-- 目安達成時間 -->
    <div class="u-margin__20b">
      <label for="parent-time" class="c-auth__label">目安達成時間</label>
      <input id="parent-time" type="number" name="parent-achievement_time" class="c-auth__input" v-model="parentData.achievement_time">
    </div>

    <!-- 内容 -->
    <div class="u-margin__20b">
      <label for="parent-content" class="c-auth__label">内容</label>
      <textarea id="parent-content" name="parent-content" class="c-auth__textarea" v-model="parentData.content">{{ parentData.content }}</textarea>
    </div>

    <!-- サムネイル -->
    <div class="u-margin__20b">
      <label for="parent-thumbnail" class="c-auth__label">サムネイル</label>
      <input id="parent-thumbnail" class="c-margin__20t" type="file" name="parent-thumbnail" accept="image/png,image/jpeg,image/gif" @change="handleFile"><br>
      <output v-if="parentData.preview">
        <img :src="parentData.preview" class="c-preview">
      </output>
      <img :src="'/storage/step_thumbnails/' + parentData.thumbnail" class="c-preview" v-else-if="!parentData.preview && parentData.thumbnail">
    </div>

  </div>
</template>

<script>
export default {
  props: ['parentData', 'categories'],
  methods : {
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
        this.parentData.preview = e.target.result;
      }

      // ファイルを読み込む
      reader.readAsDataURL(event.target.files[0]);

  		this.parentData.thumbnail = event.target.files[0];
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
