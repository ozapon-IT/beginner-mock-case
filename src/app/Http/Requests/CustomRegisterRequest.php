<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomRegisterRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8|max:100',
            'password_confirmation' => 'required|string|min:8|max:100|same:password',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'ユーザー名を入力してください。',
            'name.string' => 'ユーザー名は文字列で入力してください。',
            'name.max' => 'ユーザー名は100文字以内で入力してください。',
            'email.required' => 'メールアドレスを入力してください。',
            'email.string' => 'メールアドレスは文字列で入力してください。',
            'email.email' => '正しいメールアドレス形式で入力してください。',
            'email.max' => 'メールアドレスは100文字以内で入力してください。',
            'email.unique' => 'このメールアドレスは既に登録されています。',
            'password.required' => 'パスワードを入力してください。',
            'password.string' => 'パスワードは文字列で入力してください。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
            'password.max' => 'パスワードは100文字以内で入力してください。',
            'password_confirmation.required' => '確認用パスワードを入力してください。',
            'password_confirmation.string' => '確認用パスワードは文字列で入力してください。',
            'password_confirmation.min' => '確認用パスワードは8文字以上で入力してください。',
            'password_confirmation.max' => '確認用パスワードは100文字以内で入力してください。',
            'password_confirmation.same' => 'パスワードと一致しません。',
        ];
    }
}
