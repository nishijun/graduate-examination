<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EditProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Step;
use App\Models\Kid;
use App\Models\Challenge;
use App\Models\Record;
use Carbon\Carbon;

class AppController extends Controller
{
  // <-------------- TOPページ取得 -------------->
  public function index() {
    return view('home');
  }

  // <-------------- パスワードリマインダ -------------->
  public function passwordReminder(Request $request) {
    // 認証番号をセッションに保存
    if (!$request->session()->has('authNumber')) {
      //生成する文字数
      $length = 10;

      //使用する文字
      $char = '1234567890abcdefghijklmnopqrstuvwxyz';

      $charlen = mb_strlen($char);
      $result = "";

      for ($i = 1; $i <= $length; $i++) {
        $index = mt_rand(0, $charlen - 1);
        $result .= mb_substr($char, $index, 1);
      }
      $request->session()->put('authNumber', $result);
    }

    switch ($request->isAuth) {
      // メールアドレス認証＆認証番号送付
      case 0:
        $user = User::where('email', $request->email)->first();

        if (!$user) {
          return false;
        } else {
          $authNumber = $request->session()->get('authNumber');
          $result = Mail::send('email.password_reminder', ['authNumber' => $authNumber],
            function ($authNumber) use ($request) {
              $authNumber->to($request->email)->subject("パスワード再設定のご案内");
            }
          );
          return true;
        }
      // 認証番号確認
      case 1:
        if ($request->auth !== $request->session()->get('authNumber')) {
          return false;
        } else {
          $request->session()->forget('authNumber');
          return true;
        }
      // 新パスワード設定
      case 2:
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        break;
    }
  }

  // <-------------- プロフィール編集 -------------->
  public function editProfile (EditProfileRequest $request) {
    $user = User::find(Auth::id());
    $user->fill($request->all());

    if ($request->thumbnail) {
      // 画像の拡張子を取得する
      $extension = $request->thumbnail->extension();

      // 画像名指定
      $thumbnail_path = Carbon::now() . '_' . Auth::id() . '.' . $extension;

      // ストアに画像格納
      $request->thumbnail->storeAs('public/user_thumbnails', $thumbnail_path);

      $user->thumbnail = $thumbnail_path;
    }

    $user->save();
    return response($user, 201);
  }

  // <-------------- 退会 -------------->
  public function withdrawal() {
    User::find(Auth::id())->delete();
    Step::where('user_id', Auth::id())->delete();
    Kid::whereHas('step', function($q) {
      $q->where('user_id', Auth::id());
    })->delete();
    Challenge::where('user_id', Auth::id())->delete();
    Record::where('user_id', Auth::id())->delete();
  }
}
