<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateStepRequest;
use App\Http\Requests\EditStepRequest;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\Step;
use App\Models\Kid;
use App\Models\Challenge;
use App\Models\Record;

class StepController extends Controller {

  // <-------------- STEP登録 -------------->
  public function create (CreateStepRequest $request) {
    $step = new Step();
    $step->fill($request->all());

    if ($request->thumbnail) {
      // 画像の拡張子を取得する
      $extension = $request->thumbnail->extension();

      // 画像名指定
      $thumbnail_path = Carbon::now() . '_' . Auth::id() . '.' . $extension;

      // ストアに画像格納
      $request->thumbnail->storeAs('public/step_thumbnails', $thumbnail_path);

      $step->thumbnail = $thumbnail_path;
    }

    Auth::user()->steps()->save($step);

    return response($step, 201);
  }

  // <-------------- STEP編集画面データ取得 -------------->
  public function getData(Request $request) {
    $step = Step::where('id', $request->stepId)->first();
    $kids = Kid::where('step_id', $request->stepId)->get();

    $results = [$step, $kids];
    return $results;
  }

  // <-------------- STEP編集 -------------->
  public function edit(EditStepRequest $request) {
    // 親STEP編集
    $step = Step::find($request->p_id);
    $step->title = $request->p_title;
    $step->category_id = $request->p_category_id;
    $step->achievement_time = $request->p_achievement_time;
    $step->content = $request->p_content;

    if ($request->p_thumbnail) {
      // 画像の拡張子を取得する
      $extension = $request->p_thumbnail->extension();

      // 画像名指定
      $thumbnail_path = Carbon::now() . '_' . Auth::id() . '.' . $extension;

      // ストアに画像格納
      $request->p_thumbnail->storeAs('public/step_thumbnails', $thumbnail_path);

      $step->thumbnail = $thumbnail_path;
    }
    $step->save();

    // 子STEP編集
    $i = 0;
    while (isset($request->order[$i])) {
      $kid = Kid::where('step_id', $request->p_id)->where('order', $request->order[$i])->first();

      if ($kid) {
        // 既に子STEPが存在していた場合（編集）
        $kid->title = $request->title[$i];
        $kid->achievement_time = $request->achievement_time[$i];
        $kid->content = $request->content[$i];
      } else {
        // 新しく子STEPを作る場合
        $kid = new Kid();
        $kid->title = $request->title[$i];
        $kid->achievement_time = $request->achievement_time[$i];
        $kid->content = $request->content[$i];
      }
      $kid->step_id = $request->p_id;
      $kid->order = $request->order[$i];

      if (isset($request->thumbnail[$i])) {
        // 画像の拡張子を取得する
        $extension = $request->thumbnail[$i]->extension();

        // 画像名指定
        $thumbnail_path = Carbon::now() . '_' . $i .'_' . Auth::id() . '.' . $extension;

        // ストアに画像格納
        $request->thumbnail[$i]->storeAs('public/kid_thumbnails', $thumbnail_path);

        $kid->thumbnail = $thumbnail_path;
      }
      $kid->save();
      $i++;
    }

    return response($step, 201);
  }

  // <-------------- 子STEP削除 -------------->
  public function kidDelete(Request $request) {
    // 子STEP削除
    Kid::find($request->kid_id)->forceDelete();
    Record::where('kid_id', $request->id)->delete();

    // 子STEP番号繰り下げ処理
    $kids = Kid::where('step_id', $request->parent_id)->get();
    foreach ($kids as $kid) {
      if ($kid->order > $request->kid_order) {
        $result = Kid::find($kid->id);
        $result->order -= 1;
        $result->save();
      }
    }
  }

  // <-------------- STEP削除 -------------->
  public function delete(Request $request) {
    Step::find($request->id)->forceDelete();
    Kid::where('step_id', $request->id)->forceDelete();
    Challenge::where('step_id', $request->id)->delete();
    Record::whereHas('kid', function($q) use($request) {
      $q->where('step_id', $request->id);
    })->delete();
  }

  // <-------------- STEPカテゴリー取得 -------------->
  public function getCategories() {
    return Category::all();
  }

  // <-------------- STEP一覧ページ情報取得 -------------->
  public function steps () {
    return Step::with('category')->get();
  }

  // <-------------- マイページ情報取得 -------------->
  public function mydata() {
    $mySteps = Step::where('user_id', Auth::id())->with('category')->get();
    $myChallenges = Challenge::where('user_id', Auth::id())->get();
    $myChallengeSteps = [];

    foreach ($myChallenges as $myChallenge) {
      $step = Step::where('id', $myChallenge->step_id)->with('category')->first();
      $kidsCount = Kid::where('step_id', $step->id)->count();
      $achievementCount = Record::where('user_id', Auth::id())->whereHas('kid', function($q) use($step) {
        $q->where('step_id', $step->id);
      })->count();

      $step->kidsCount = $kidsCount;
      $step->achievementCount = $achievementCount;
      $myChallengeSteps[] = $step;
    }

    $results = [$mySteps, $myChallengeSteps];
    return $results;
  }

  // <-------------- 親STEP詳細ページ情報取得 -------------->
  public function show ($stepId) {
    // 親STEP情報
    $step = Step::where('id', $stepId)->with('category', 'user')->first();
    // 子STEP情報
    $kids = Kid::where('step_id', $stepId)->orderBy('order', 'asc')->get();
    // STEP挑戦履歴
    $challenge = Challenge::where('user_id', Auth::id())->where('step_id', $stepId)->first();
    // STEPクリア状況
    $records = Record::where('user_id', Auth::id())->get();

    $results = [$step, $kids, $challenge, $records];
    return $results;
  }

  // <-------------- STEP挑戦 -------------->
  public function challenge(Request $request) {
    $challenge = new Challenge();
    $challenge->fill($request->all())->save();
  }

  // <-------------- STEP挑戦解除 -------------->
  public function release(Request $request) {
    Challenge::where('user_id', $request->user_id)->where('step_id', $request->step_id)->first()->delete();

    Record::whereHas('kid', function($q) use($request) {
      $q->where('step_id', $request->step_id);
    })->delete();
  }

  // <-------------- 子STEP詳細ページ情報取得 -------------->
  public function kid($stepId, $kidId) {
    // 子STEP情報
    $kid = Kid::where('id', $kidId)->first();

    // クリア有無
    $record = Record::where('user_id', Auth::id())->where('kid_id', $kidId)->first();

    $results = [$stepId, $kid, $record];
    return $results;
  }

  // <-------------- STEPクリア -------------->
  public function clear(Request $request) {
    $record = new Record();
    $record->fill($request->all())->save();
  }

  // <-------------- STEPクリア解除 -------------->
  public function unclear (Request $request) {
    Record::where('user_id', $request->user_id)->where('kid_id', $request->kid_id)->delete();
  }
}
