## アプリケーション名
<b>Atte勤怠管理システム</b><br>
登録したユーザーが各ボタンを押して、勤務開始・終了時間、休憩開始・終了時間を記録することができる勤怠管理アプリケーションです。<br> 
1日の勤務時間や休憩時間をリアルタイムで記録し、日ごとの勤務時間を確認できます。<br> 
日付が変わると、自動的に翌日の勤務に切り替わります。<br>
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
![table1](https://github.com/user-attachments/assets/a9d8c3e6-77d3-4504-8ac4-cbb6d1c3ff02)
![table2](https://github.com/user-attachments/assets/0375fae1-8006-4ab4-a15a-ced044115662)

## ER図
![er](https://github.com/user-attachments/assets/da08138a-d038-43ab-92a2-b9da91643e6a)

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
- 本番環境 : https://atte-dves.onrender.com <br>
- phpMyAdmin:：http://localhost:8080/

## その他　<b>アプリケーションURL(特に本番環境について)</b>
Dockerfile,default.conf,docker-compose.ymlファイルは開発環境と本番環境のコードに分けて記載してあります。通常は開発環境のコードを表示、本番環境のコードをコメントアウトしております。<br>また、Render.comは従量課金制の為、無料で利用するために通常はサービスを停止しております。採点の際はサービスをONにしますのでご連絡いただきますよう、よろしくお願いいたします。<br>
メールアドレス: kzytty199120@yahoo.co.jp
