<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'user_id' => User::find(1)->id,
                'category_id' => Category::find(5)->id,
                'condition_id' => Condition::find(1)->id,
                'name' => '腕時計',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => '15000',
                'status' => '在庫あり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
            ],
            [
                'user_id' => User::find(2)->id,
                'category_id' => Category::find(15)->id,
                'condition_id' => Condition::find(2)->id,
                'name' => 'HDD',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => '5000',
                'status' => '在庫あり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
            ],
            [
                'user_id' => User::find(3)->id,
                'category_id' => Category::find(17)->id,
                'condition_id' => Condition::find(3)->id,
                'name' => '玉ねぎ3束',
                'description' => '新鮮な玉ねぎ3束のセット',
                'price' => '300',
                'status' => '在庫あり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
            ],
            [
                'user_id' => User::find(4)->id,
                'category_id' => Category::find(1)->id,
                'condition_id' => Condition::find(4)->id,
                'name' => '革靴',
                'description' => 'クラシックなデザインの革靴',
                'price' => '4000',
                'status' => '在庫あり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
            ],
            [
                'user_id' => User::find(5)->id,
                'category_id' => Category::find(15)->id,
                'condition_id' => Condition::find(1)->id,
                'name' => 'ノートPC',
                'description' => '高性能なノートパソコン',
                'price' => '45000',
                'status' => '在庫あり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
            ],
            [
                'user_id' => User::find(6)->id,
                'category_id' => Category::find(16)->id,
                'condition_id' => Condition::find(2)->id,
                'name' => 'マイク',
                'description' => '高音質のレコーディング用マイク',
                'price' => '8000',
                'status' => '在庫あり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
            ],
            [
                'user_id' => User::find(7)->id,
                'category_id' => Category::find(1)->id,
                'condition_id' => Condition::find(3)->id,
                'name' => 'ショルダーバッグ',
                'description' => 'おしゃれなショルダーバッグ',
                'price' => '3500',
                'status' => '在庫あり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
            ],
            [
                'user_id' => User::find(8)->id,
                'category_id' => Category::find(18)->id,
                'condition_id' => Condition::find(4)->id,
                'name' => 'タンブラー',
                'description' => '使いやすいタンブラー',
                'price' => '500',
                'status' => '在庫あり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
            ],
            [
                'user_id' => User::find(9)->id,
                'category_id' => Category::find(19)->id,
                'condition_id' => Condition::find(1)->id,
                'name' => 'コーヒーミル',
                'description' => '手動のコーヒーミル',
                'price' => '4000',
                'status' => '在庫あり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
            ],
            [
                'user_id' => User::find(10)->id,
                'category_id' => Category::find(6)->id,
                'condition_id' => Condition::find(2)->id,
                'name' => 'メイクセット',
                'description' => '便利なメイクアップセット',
                'price' => '2500',
                'status' => '在庫あり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
