<template>
  <div>

    <div class="c-auth-child" v-if="childData.length >= 1" v-for="(item, index) in childData" :key="item.id">

      <!-- STEP削除ボタン -->
      <i class="far fa-times-circle c-delete" @click="deleteForm(index)"></i>

      <!-- STEP番号 -->
      <p class="c-auth-child-order">STEP {{ item.order }}</p>

      <!-- STEP名 -->
      <div class="u-margin__20b">
        <label for="child-title" class="c-auth__label">タイトル</label>
        <input id="child-title" type="text" name="title" class="c-auth__input" v-model="item.title">
      </div>

      <!-- 目安達成時間 -->
      <div class="u-margin__20b">
        <label for="child-achievement_time" class="c-auth__label">目安達成時間</label>
        <input id="child-child-achievement_time" type="number" name="achievement_time" class="c-auth__input" v-model="item.achievement_time">
      </div>

      <!-- 内容 -->
      <div class="u-margin__20b">
        <label for="child-content" class="c-auth__label">内容</label>
        <textarea id="child-content" name="content" class="c-auth__textarea" v-model="item.content">{{ item.content }}</textarea>
      </div>

      <!-- サムネイル -->
      <div class="u-margin__20b">
        <label for="child-thumbnail" class="c-auth__label">サムネイル</label>
        <input id="child-thumbnail" class="c-margin__20t" type="file" name="thumbnail" @change="handleFile($event, index)" accept="image/png,image/jpeg,image/gif"><br>
        <output v-if="item.preview">
          <img :src="item.preview" class="c-preview">
        </output>
        <img :src="'/storage/kid_thumbnails/' + item.thumbnail" class="c-preview" v-else-if="!item.preview && item.thumbnail">
      </div>

    </div>

    <!-- STEP追加ボタン -->
    <i class="far fa-plus-square c-plus" @click="addForm"></i>

  </div>
</template>

<script>
export default {
  props: ['childData'],
  methods: {
    // STEP追加
    addForm() {
      let order = this.childData.length + 1;
      this.childData.push({
        order,
        title: '',
        achievement_time: '',
        content: '',
        thumbnail: '',
        preview: null
      });
    },

    // STEP削除
    async deleteForm(index) {
      if (confirm('本当に削除しますか？')) {
        if (this.childData[index].id) {
          const formData = new FormData();
          formData.append('kid_id', this.childData[index].id);
          formData.append('kid_order', this.childData[index].order);
          formData.append('parent_id', this.$attrs.parentData.id);
          const response = await axios.post('/api/kid/delete', formData);
        }
        this.$emit('getStepData');
      }
    },

    // イメージビュー
    handleFile (event, index) {
      // 何も選択されていなかったら処理中断
      if (event.target.files.length === 0) {
        this.reset(index);
        return false;
      }

      // ファイルが画像ではなかったら処理中断
      if (!event.target.files[0].type.match('image.*')) {
        this.reset(index);
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
        this.$set(this.childData[index], 'preview', e.target.result);
      }

      // ファイルを読み込む
      reader.readAsDataURL(event.target.files[0]);

  		this.childData[index].thumbnail = event.target.files[0];
  	},

    // 入力欄の値とプレビュー表示をクリア
    reset (index) {
      this.childData[index].preview = '';
      this.childData[index].thumbnail = null;
      this.$el.querySelector('input[type="file"]').value = null;
    }
  }
}
</script>
