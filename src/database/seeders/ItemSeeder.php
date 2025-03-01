<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // すべてのユーザーと状態を取得
        $users = User::all();
        $conditions = Condition::all();

        // ユーザーや状態が存在しない場合に作成
        if ($users->isEmpty()) {
            $users = User::factory(10)->create();
        }

        if ($conditions->isEmpty()) {
            $conditions = Condition::factory()->count(4)->sequence(
                ['name' => '良好'],
                ['name' => '目立った傷や汚れなし'],
                ['name' => 'やや傷や汚れあり'],
                ['name' => '状態が悪い']
            )->create();
        }

        // カテゴリーを取得または作成
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $categoriesData = [
                ['name' => 'ファッション'],
                ['name' => '家電'],
                ['name' => 'インテリア'],
                ['name' => 'レディース'],
                ['name' => 'メンズ'],
                ['name' => 'コスメ'],
                ['name' => '本'],
                ['name' => 'ゲーム'],
                ['name' => 'スポーツ'],
                ['name' => 'キッチン'],
                ['name' => 'ハンドメイド'],
                ['name' => 'アクセサリー'],
                ['name' => 'おもちゃ'],
                ['name' => 'ベビー・キッズ'],
                ['name' => '情報家電'],
                ['name' => 'PC関連'],
                ['name' => '黒物家電'],
                ['name' => 'AV機器'],
                ['name' => '食品'],
                ['name' => '食器'],
                ['name' => 'グッズ'],
            ];

            foreach ($categoriesData as $categoryData) {
                Category::create($categoryData);
            }

            $categories = Category::all();
        }

        // カテゴリーマッピングを作成
        $categoryMap = $categories->pluck('id', 'name')->toArray();

        $items = [
            [
                'user_id' => $users->random()->id,
                'condition_id' => $conditions->random()->id,
                'name' => '腕時計',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => '15000',
                'status' => '在庫あり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'category_names' => ['ファッション','メンズ'],
            ],
            [
                'user_id' => $users->random()->id,
                'condition_id' => $conditions->random()->id,
                'name' => 'HDD',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => '5000',
                'status' => '在庫あり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'category_names' => ['情報家電','PC関連'],
            ],
            [
                'user_id' => $users->random()->id,
                'condition_id' => $conditions->random()->id,
                'name' => '玉ねぎ3束',
                'description' => '新鮮な玉ねぎ3束のセット',
                'price' => '300',
                'status' => '在庫あり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'category_names' => ['食品','キッチン'],
            ],
            [
                'user_id' => $users->random()->id,
                'condition_id' => $conditions->random()->id,
                'name' => '革靴',
                'description' => 'クラシックなデザインの革靴',
                'price' => '4000',
                'status' => '在庫あり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'category_names' => ['ファッション','メンズ'],
            ],
            [
                'user_id' => $users->random()->id,
                'condition_id' => $conditions->random()->id,
                'name' => 'ノートPC',
                'description' => '高性能なノートパソコン',
                'price' => '45000',
                'status' => '在庫あり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'category_names' => ['情報家電','PC関連'],
            ],
            [
                'user_id' => $users->random()->id,
                'condition_id' => $conditions->random()->id,
                'name' => 'マイク',
                'description' => '高音質のレコーディング用マイク',
                'price' => '8000',
                'status' => '在庫あり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'category_names' => ['黒物家電','AV機器'],
            ],
            [
                'user_id' => $users->random()->id,
                'condition_id' => $conditions->random()->id,
                'name' => 'ショルダーバッグ',
                'description' => 'おしゃれなショルダーバッグ',
                'price' => '3500',
                'status' => '在庫あり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'category_names' => ['ファッション','レディース'],
            ],
            [
                'user_id' => $users->random()->id,
                'condition_id' => $conditions->random()->id,
                'name' => 'タンブラー',
                'description' => '使いやすいタンブラー',
                'price' => '500',
                'status' => '在庫あり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'category_names' => ['食器','キッチン'],
            ],
            [
                'user_id' => $users->random()->id,
                'condition_id' => $conditions->random()->id,
                'name' => 'コーヒーミル',
                'description' => '手動のコーヒーミル',
                'price' => '4000',
                'status' => '在庫あり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'category_names' => ['インテリア','グッズ'],
            ],
            [
                'user_id' => $users->random()->id,
                'condition_id' => $conditions->random()->id,
                'name' => 'メイクセット',
                'description' => '便利なメイクアップセット',
                'price' => '2500',
                'status' => '在庫あり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'category_names' => [ 'レディース','コスメ'],
            ],
        ];

        foreach ($items as $itemData) {
            $categoryNames = $itemData['category_names'];
            unset($itemData['category_names']);

            // 画像パスの生成処理
            $imageUrl = $itemData['image_path'];
            $imageContents = file_get_contents($imageUrl); // URLから画像データを取得

            // ファイル拡張子を取得
            $extension = pathinfo($imageUrl, PATHINFO_EXTENSION);

            // ファイル名をランダムな文字列に変更
            $imageName = 'items/' . Str::random(20) . '.' . $extension;

            Storage::disk('public')->put($imageName, $imageContents); // storage/app/public/items に保存

            // itemsテーブルには相対パスのみを保存
            $itemData['image_path'] = $imageName;

            // 商品を作成
            $item = Item::create($itemData);

            // カテゴリーIDを取得
            $categoryIds = array_map(function ($name) use ($categoryMap) {
                return $categoryMap[$name];
            }, $categoryNames);

            // 商品にカテゴリーをアタッチ
            $item->categories()->attach($categoryIds);
        }
    }
}
