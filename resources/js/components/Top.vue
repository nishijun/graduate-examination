<template>
  <main>
    
    <!-- ヒーロービュー -->
    <section class="p-hero l-main1">
      <div class="p-hero__image">
        <div class="p-hero__image-cover u-padding__30x">
          <h1 class="c-hero__message">I will tell you the STEP.</h1>
        </div>
      </div>
      <div class="p-hero__content u-padding__30x">
        <h2 class="c-hero__message2">あなたの人生の<span>STEP</span>を共有しよう</h2>
        <p class="c-hero__sentence">プログラミングや英語、スポーツなど、おすすめの学習ステップや教材を他の人に紹介して、あなたがこれまでどのように学んできたのか、またそのコツなどをシェアしましょう。<br class="u-display__sm-delete"><br class="u-display__sm-delete">またこのサービスを通じて、これからあなたが身につけたいと思っているスキルや能力など、既にSTEPを共有してくれている人達から修得するための知恵を学び、そして挑戦していきましょう。</p>
        <button class="c-btn u-margin__auto">
          <routerLink :to="{name: 'signup'}" tag="a">はじめる</routerLink>
        </button>
      </div>
    </section>

    <!-- イメージ＆テキスト -->
    <section class="p-imageText">
      <h2 class="p-imageText__title">STEP例</h2>
      <div class="p-imageText__box">
        <img src="/img/top-img1.jpg" alt="TOPイメージ1" class="p-imageText__box-image">
          <p class="p-imageText__box-text">フリーエンジニアとして年収1000万円を達成するステップ</p>
      </div>
      <div class="p-imageText__box">
        <img src="/img/top-img2.jpg" alt="TOPイメージ2" class="p-imageText__box-image">
        <p class="p-imageText__box-text">3ポイントシュートの練習方法</p>
      </div>
      <div class="p-imageText__box">
        <img src="/img/top-img3.jpg" alt="TOPイメージ3" class="p-imageText__box-image">
        <p class="p-imageText__box-text">ミックスボイスの習得方法</p>
      </div>
    </section>

    <!-- 機能一覧 -->
    <section class="p-function">
      <h2 class="p-function__title">充実した機能で<br>あなたの「達成したい」を叶える</h2>
      <div class="p-function__items">
        <div class="p-function__item">
          <i class="fas fa-share p-function__item-logo"></i>
          <p class="p-function__item-title">STEP共有</p>
          <p class="p-function__item-text">あなたが持っているノウハウや勉強・練習方法などのSTEPを共有</p>
        </div>
        <div class="p-function__item">
          <i class="fas fa-dumbbell p-function__item-logo"></i>
          <p class="p-function__item-title">STEP挑戦</p>
          <p class="p-function__item-text">自分が達成したい・身につけたいと思っているSTEPに挑戦</p>
        </div>
        <div class="p-function__item">
          <i class="fas fa-trophy p-function__item-logo"></i>
          <p class="p-function__item-title">STEPクリア</p>
          <p class="p-function__item-text">クリアしたら適宜進捗を反映させよう！STEPが終わる頃にはあなたは能力を身につけているはず</p>
        </div>
      </div>
    </section>

  </main>
</template>
