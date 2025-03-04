<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
            'body' => 'required|string|max:400',
            'image_path' => 'nullable|image|mimes:jpeg,png|max:2048',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     * 
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'body.required' => '本文を入力してください',
            'body.string' => '本文は文字列で入力してください',
            'body.max' => '本文は400文字以内で入力してください',
            'image_path.image' => '画像ファイルでアップロードしてください',
            'image_path.mimes' => ' 「.png」または「.jpeg」形式でアップロードしてください',
            'image_path.max' => '2MB以下のファイルをアップロードしてください',
        ];
    }
}
