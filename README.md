# COACHTECHフリマアプリ
## 環境構築

### Dockerビルド

1. `git clone git@github.com:ozapon-IT/check-test.git`
2. `docker-compose up -d --build`

\* MySQL、phpMyAdmin、MailHogは、OSによって起動しない場合があるのでそれぞれのPCに合わせて `docker-compose.yml` ファイルを編集してください。

### Laravel環境構築

1. `docker-compose exec php bash`
2. `composer install`
3. `cp .env.example .env`
4. `php artisan key:generate`
5. `php artisan storage:link`
6. `php artisan migrate`
7. `php artisan db:seed`

### Stripeセットアップ

1. Stripeアカウントの作成とAPIキーの取得
- Stripeの[公式サイト](https://dashboard.stripe.com/register)でテストアカウントを作成します。
- ダッシュボードから「テスト用APIキー」を取得します。
2. 環境変数の設定

`.env` ファイルに以下を追加します
```
STRIPE_SECRET_KEY=your_test_secret_key
STRIPE_PUBLIC_KEY=your_test_public_key
```
> 注：your_test_secret_key と your_test_public_key は、Stripeダッシュボードから取得したテスト用のキーに置き換えてください。

### Webhook のローカルテスト方法

ホストマシン（Docker が動作しているマシン）に Stripe CLI をインストールして Webhook をローカルでテストします。

1. Stripe CLI のインストール
`brew install stripe/stripe-cli/stripe`
2. Stripe CLI の認証
`stripe login`
3. Webhook URL の確認
`http://localhost/stripe/webhook`
4. Webhook リスニングの設定
- Stripe CLI を使用して、Webhook イベントを Laravel アプリケーションに転送します。
  
`stripe listen --forward-to http://localhost/stripe/webhook`
- `stripe listen`実行時に表示されるシークレットキーを確認。

`Ready! Your webhook signing secret is whsec_xxxxxxxxxx`
- .envファイルに設定
  
`STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxx`

5. 動作確認
- カード払いのテスト
  - アプリケーションの商品購入画面で「カード払い」を選択し、購入ボタンを押します。
  - Stripeのテスト用カード番号（例：4242 4242 4242 4242）を使用して決済を完了します。
  - マイページにリダイレクトされ、購入処理が完了することを確認します。
- コンビニ払いのテスト
  - アプリケーションの商品購入画面で「コンビニ払い」を選択し、購入ボタンを押します。
  - Stripeのテスト用確認番号（例：22222222220）を使用して決済を完了します。
  - http://localhost/mypage?tab=buy にアクセスし、購入が完了していることを確認します。
> コンビニ払いの場合、実際の支払い完了はユーザーがコンビニで支払いを完了した後となりますが、テストでは、即支払い完了や3分後支払い完了などのシミュレーションが可能です。

6. 参考資料
- [Stripe公式ドキュメント - Stripe CLI](https://docs.stripe.com/stripe-cli/overview)
- [Stripe公式ドキュメント - コンビニ決済](https://stripe.com/docs/payments/konbini/accept-a-payment?platform=web&ui=checkout)

---

## 使用技術

- Laravel Framework 10.48.23
- Laravel Fortify 1.24
- PHP 8.2.26 (cli)
- MySQL 9.1.0 for Linux on x86_64
- Nginx 1.27.2
- phpMyAdmin 5.2.1
- MailHog 1.14.7
- Stripe Stripe-php 16.2
- Stripe CLI 1.21.11

---

## ER図
![COACHTEChフリマ:ER図](https://github.com/user-attachments/assets/385cf5fa-6a26-4c71-ab9c-15e23acb5f92)


---

## URL

- 開発環境 : [http://localhost](http://localhost)  
- phpMyAdmin : [http://localhost:8080](http://localhost:8080)
- MailHog : [http://localhost:8025](http://localhost:8025)

