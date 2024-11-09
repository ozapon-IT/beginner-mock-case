<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name' => 'required|string',
            'description' => 'required|string|max:255',
            'image_path' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'category_id' => 'required|array|min:1',
            'category_id.*' => 'exists:categories,id',
            'condition_id' => 'required|exists:conditions,id',
            'price' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '商品名を入力してください。',
            'name.string' => '商品名は文字列で入力してください。',
            'description.required' => '商品の説明を入力してください。',
            'description.string' => '商品の説明は文字列で入力してください。',
            'description.max' => '商品の説明は255文字以内で入力してください。',
            'image_path.required' => '商品画像を選択してください。',
            'image_path.image' => 'アップロード可能な画像はJPEGまたはPNGのみです。',
            'category_id.required' => 'カテゴリーを選択してください。',
            'condition_id.required' => '商品の状態を選択してください。',
            'price.required' => '販売価格を入力してください。',
            'price.numeric' => '販売価格は数値で入力してください。',
            'price.min' => '販売価格は0円以上でなければなりません。',
        ];
    }
}
