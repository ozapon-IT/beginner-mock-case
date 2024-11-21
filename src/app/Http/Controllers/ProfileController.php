<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    public function showProfileForm()
    {
        $profile = Profile::where('user_id', Auth::id())->first();
        $user = Auth::user();

        return view('profile', compact('profile', 'user'));
    }

    public function updateProfile(ProfileRequest $request)
    {
        $user = Auth::user();

        // ユーザー名（usersテーブル）の更新
        $user->update([
            'name' => $request->input('name')
        ]);

        $profileData = $request->only(['postal_code', 'address', 'building']);

        // 画像の処理
        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('profiles', 'public');
            $profileData['image_path'] = $path;
        }

        // プロフィールの存在確認
        $profileExists = Profile::where('user_id', $user->id)->exists();

        // プロフィールの更新または作成（profilesテーブル）
        Profile::updateOrCreate(['user_id' => $user->id], $profileData);

        // 初回プロフィール設定の場合、商品一覧画面にリダイレクト
        if (!$profileExists) {
            return redirect()->route('top', ['tab' => 'mylist']);
        }

        // 2回目以降はマイページにリダイレクト
        return redirect()->route('mypage')->with('success', 'プロフィールを更新しました。');
    }

}
