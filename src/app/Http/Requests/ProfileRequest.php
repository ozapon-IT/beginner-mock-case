<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'postal_code' => 'required|regex:/^\d{3}-\d{4}$/',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
            'image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    /**
     * カスタムエラーメッセージの定義
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.string' => 'ユーザー名は文字列で入力してください。',
            'name.max' => 'ユーザー名は255文字以下で入力してください。',
            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.regex' => '郵便番号はハイフンありの8文字で入力してください。',
            'address.required' => '住所を入力してください。',
            'address.string' => '住所は文字列で入力してください。',
            'address.max' => '住所は255文字以下で入力してください。',
            'building.string' => '建物名は文字列で入力してください。',
            'building.max' => '建物名は255文字以下で入力してください。',
            'image.mimes' => 'プロフィール画像はjpg、jpeg、またはpng形式でアップロードしてください。',
            'image.max' => 'プロフィール画像は2MB以下のファイルを選択してください。',
        ];
    }
}
