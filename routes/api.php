<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// ユーザ登録
Route::post('/signup', 'Auth\RegisterController@register')->name('signup');

// ログイン
Route::post('/login', 'Auth\LoginController@login')->name('login');

// ログアウト
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

// パスワードリマインダ
Route::post('/password/reminder', 'AppController@passwordReminder')->name('password.reminder');

// マイページデータ取得
Route::get('/step/mydata', 'StepController@mydata')->name('step.mydata');

// STEP登録
Route::post('/step/create', 'StepController@create')->name('step.create');

// プロフィール編集
Route::post('/user/edit', 'AppController@editProfile')->name('user.edit');

// STEP一覧
Route::get('/steps', 'StepController@steps')->name('steps');

// 親STEP詳細
Route::get('/step/{stepId}', 'StepController@show')->name('step.show');

// STEP挑戦
Route::post('/step/challenge', 'StepController@challenge')->name('step.challenge');

// STEP編集画面データ取得
Route::post('/step/getData', 'StepController@getData')->name('step.getData');

// STEP編集
Route::post('/step/edit', 'StepController@edit')->name('step.edit');

// 子STEP削除
Route::post('/kid/delete', 'StepController@kidDelete')->name('kid.delete');

// STEP削除
Route::post('/step/delete', 'StepController@delete')->name('step.delete');

// STEP挑戦解除
Route::post('/step/release', 'StepController@release')->name('step.release');

// 子STEP詳細
Route::get('/step/{stepId}/{kidId}', 'StepController@kid')->name('step.kid');

// STEPクリア
Route::post('/step/clear', 'StepController@clear')->name('step.clear');

// STEPクリア解除
Route::post('/step/unclear', 'StepController@unclear')->name('step.unclear');

// ログインユーザー
Route::get('/user', function() {
  return Auth::user();
})->name('user');

// カテゴリー取得
Route::get('/categories', 'StepController@getCategories')->name('categories');

// 退会
Route::get('/withdrawal', 'AppController@withdrawal')->name('withdrawal');
