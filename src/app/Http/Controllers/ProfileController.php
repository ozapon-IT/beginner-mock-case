<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    public function showProfileForm()
    {
        // ログイン中のユーザーのプロフィール情報を取得
        $profile = Profile::where('user_id', Auth::id())->first();
        $user = Auth::user(); // ユーザー情報を取得

        // profile.blade.php に `$profile` と `$user` 変数として渡す
        return view('profile', compact('profile', 'user'));
    }

    public function updateProfile(ProfileRequest $request)
    {
        // ログイン中のユーザーを取得
        $user = Auth::user();

        // ユーザー名（usersテーブル）の更新
        $user->update([
            'name' => $request->input('name'),
        ]);

        // プロフィールデータの準備（profilesテーブル）
        $profileData = $request->only(['postal_code', 'address', 'building']);

        // 画像の処理
        if ($request->hasFile('image')) {
            // 画像ファイルを保存
            $path = $request->file('image')->store('avatars', 'public');
            $profileData['image_path'] = $path;
        }

        // プロフィールの更新または作成（profilesテーブル）
        Profile::updateOrCreate(
            ['user_id' => $user->id], // 条件
            $profileData
        );

        return redirect()->route('profile');
    }

}
