## アプリケーション名
<b>Rese飲食店予約サービスアプリ</b><br>
登録したユーザー(利用者)がホーム画面にて東京・大阪・福岡のエリア、寿司・焼肉・居酒屋・イタリアン・ラーメンのジャンルで登録されている各店舗の予約が出来るアプリケーションです。ホーム画面では、検索したいエリア、ジャンルを右上のプルダウンメニューから選んで店舗を絞り込んだり、キーワードを入力することで店舗を絞り込むソート機能、ログインして店舗カードの右下にあるハートマークをクリックすることで店舗のお気に入り追加・削除を自由に行うことが可能です。また、詳しくみるボタンをクリックすると店舗の詳細を閲覧したり、画像を保存するボタンをクリックすることで画像が保存出来る他、ログインすることによって店舗の予約を行うことが可能です。<br> 
マイページの画面では、ユーザー(利用者)は自由にマイページにて予約情報の更新・キャンセル(削除)、お気に入り店舗の追加・削除が出来るだけでなく、QRコードを発行することによって店舗側が照合することが出来ます。<br>また、予約も通常の予約と、Stripeというオンライン決済を利用した予約が出来るようになっております。<br>
他にもユーザー(利用者)が予約した日時が到来した際に、予約当日の朝に予約のリマインダーメールを送信(メール自体はlocalhost:8025のmailhog宛に届く)することが出来たり、予約のリマインダーメールの「レビューする」というリンクをクリックすることで、５段階評価とコメントを記載することが可能となっております。<br>
５段階評価と記載したコメントは来店した店舗の詳細画面(詳しくみるボタンをクリックすることで表示)にて反映され、「〇〇件の口コミ」と記載されている箇所をクリックすることで、モーダルウィンドウが表示され、コメントを記載したユーザーや記載されたコメントを確認することが出来る仕組みとなっております。<br>
詳細画面の店舗画像の下に画像を保存するというボタンが存在し、表示されている店舗画像をストレージに保存することが可能です。<br>
管理者や店舗代表者に関しては、追加や登録されているユーザー(利用者)全員にお知らせメールを送信(メール自体はlocalhost:8025のmailhog宛に届く)することが出来、店舗代表者は登録されている全店舗の情報の更新や新店舗の追加が出来る仕組みになっております。<br>

<b>-基本機能-</b><br>
・ログイン後、まず「勤務開始」ボタンのみが押せる状態になります。<br>
・勤務開始後、「休憩開始」および「勤務終了」ボタンが使用可能になります。<br>
・休憩は1日に何度でも取ることができ、休憩時間は分単位で手動設定可能です。<br>
・過去の勤務時間もカレンダーを使用して確認可能です。<br>
                            <b>ホーム画面</b>
![ホーム画面](https://github.com/user-attachments/assets/e6f2e042-9e6f-4394-93b7-072780848120)
                           <b>タイマー画面</b>
![タイマー画面](https://github.com/user-attachments/assets/9adff991-07b2-4e72-ad24-ccbb79c12c31)
                           <b>ログイン画面</b>
![ログイン画面](https://github.com/user-attachments/assets/c0c4911a-7209-4fb4-a780-a020dee84018)
                            <b>会員登録画面</b>
![会員登録画面](https://github.com/user-attachments/assets/53d94cc5-247f-4bdc-8462-2aed207b5fa0)
                             <b>日付一覧画面</b>
![日付一覧画面](https://github.com/user-attachments/assets/172e7ad9-9ecf-4be0-aea1-4acbdae60901)

## 作成した目的
新規事業を立ち上げた企業向けの勤怠管理システムとして開発しました。<br> 
このアプリを通じて、労務管理だけでなく、人事評価にも役立てることが可能です。
## アプリケーションURL
http://localhost/ (開発環境)<br>
https://atte-dves.onrender.com デプロイ(本番環境)
## 他のリポジトリ
git@github.com:kazuya0290/atte.git<br>
→　勤怠管理システム、認証機能を含むリポジトリです。
## 機能一覧
1.ログイン機能<br>
2.会員登録機能<br>
3.勤怠管理機能<br>
4.勤怠履歴確認機能・カレンダー機能

## 使用技術(実行環境)
-Laravel Framework 8.83.8<br>
-PHP 7.4.9<br>
-JavaScript<br>
-MySQL-> php artisan migrateによるマイグレーション<br>

## テーブル図

![areas](https://github.com/user-attachments/assets/d9b3ae8f-c605-4e5e-aee9-420a2c9afac9)

![details](https://github.com/user-attachments/assets/2d3247ee-f87a-465e-9334-d451f7d9b5e8)

![favorites](https://github.com/user-attachments/assets/445f0546-098b-4d0b-bbe7-8316dd4eef5d)

![genres](https://github.com/user-attachments/assets/49ad7721-9717-4060-aefd-e1836e3a1aa2)

![representatives](https://github.com/user-attachments/assets/88a68299-2079-4b2a-b59a-987d9dce62ed)

![reservations](https://github.com/user-attachments/assets/c2f89f76-9078-48b5-afb8-c862d56aced4)

![reviews](https://github.com/user-attachments/assets/2aeace73-94d5-4198-8ecc-9a2696f0ae69)

![shops](https://github.com/user-attachments/assets/4e719628-0e3b-45b3-89a9-eece4d58a499)

![shops_users](https://github.com/user-attachments/assets/99997495-5143-4d74-b060-6e2ffb7c96b3)

![users](https://github.com/user-attachments/assets/6e818e97-8fc4-4476-8d7b-3b7d49f23ce0)

## ER図
![er](https://github.com/user-attachments/assets/ba5bbe11-ba8d-4921-bccd-eee107f2ea39)

## 環境構築
<b>- Dockerのビルド -</b><br>
1.リポジトリのクローン　→　git clone git@github.com:kazuya0290/atte.git<br>
2.Dockerデスクトップを立ち上げ、作成したコンテナを起動する。<br>
3.コンテナの起動と再ビルド　→　docker-compose up -d --build

  <b>-Laravel環境構築-</b>
1. Dockerコンテナに入る　→　docker-compose exec php bash
2. Composerのインストール　→　composer install
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.envファイルを作成
4. .envに以下の環境変数を追加<br>
DB_CONNECTION=mysql<br>
DB_HOST=mysql<br>
DB_PORT=3306<br>
DB_DATABASE=laravel_db<br>
DB_USERNAME=laravel_user<br>
DB_PASSWORD=laravel_pass<br>
5. アプリケーションキーの作成<br>
→　php artisan key:generate<br>
6. マイグレーションの実行<br>
→　php artisan migrate<br>
7. シーディングの実行<br>
→　php artisan db:seed

## URL
- 開発環境：http://localhost/ <br>
- mailhog : http://localhost:8025/<br>
- phpMyAdmin : http://localhost:8080/<br>
- 本番環境 : https://rese-dves.onrender.com (従量課金制の為、サービス停止中)

## その他　<b>アプリケーションURL(特に本番環境について)</b>
mailhogにて届いたメールを開くとhtmlメッセージが表示されないことがあります。その場合は１度メールを閉じて再度開くと表示されます。
Dockerfile,default.conf,docker-compose.ymlファイルは開発環境と本番環境のコードに分けて記載してあります。通常は開発環境のコードを表示、本番環境のコードをコメントアウトしております。<br>また、Render.comは従量課金制の為、無料で利用するために通常はサービスを停止しております。採点の際はサービスをONにしますのでご連絡いただきますよう、よろしくお願いいたします。<br>
メールアドレス: kzytty199120@yahoo.co.jp
