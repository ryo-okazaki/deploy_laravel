# 概要
LaravelのToDoアプリ
### 機能一覧
- ログイン
- ログアウト
- 会員登録
- パスワードリセットメール送信
- パスワードリセット
- フォルダ作成
- ToDo作成
- ToDo編集
- ToDo削除

# 構成
### バージョン
  - PHP：8.2
  - Laravel：9.0
  - Composer：Latest（2023-05-07時点）
  - Nginx：1.23.2

### アーキテクチャ
**ADR**(**Action Domain Responder**)で実装。  
ADRはMVC(Model View Controller)の改良版と言われており、1クラス1アクションで定義するため、シンプルなリクエストとレスポンスの流れが作れる。
また、1つのクラスの責務がはっきりすることで、ソースコードの見通しが良くなる

### テーブル定義
**folders**
No. | Column Name | Data Type  | Length | PK  | FK  | Nullable | Default | Description |
----|-------------|------------|--------|-----|-----|----------|---------|-------------|
1   | id          | INT        | 10     |  ✔   |     | ✘        |         | auto_increment |
2   | user_id     | INT        | 10     |     |  ✔   | ✘        |         | foreign_key |
3   | title       | VARCHAR    | 20     |     |     | ✘         |         |               |
4   | created_at  | TIMESTAMP  |        |     |     |  ✔        |         |              |
5   | updated_at  | TIMESTAMP  |        |     |     |  ✔        |         |              |

#### Primary Key
- id

#### Foreign Key
- user_id
  - Reference Table: users
  - Reference Column: id

**tasks**
No. | Column Name | Data Type | Length | PK  | FK  | Nullable | Default | Description |
----|-------------|----------|--------|-----|-----|----------|---------|-------------|
1   | id          | INT      | 10     |  ✔   |     | ✘        |         | auto_increment |
2   | folder_id   | INT      | 10     |     | ✔   | ✘        |         | foreign_key |
3   | title       | VARCHAR  | 100    |     |     | ✘        |         | Title of the task. |
4   | due_date    | DATE     |        |     |     | ✘        |         |             |
5   | status      | INT      |        |     |     | ✘        | 1       |             |
6   | created_at  | TIMESTAMP|        |     |     |   ✔        |         |           |
7   | updated_at  | TIMESTAMP|        |     |     |   ✔        |         |           |
#### Primary Key
- id

#### Foreign Key
- folder_id
  - Reference Table: folders
  - Reference Column: id

**users**
No. | Column Name       | Data Type  | Length | PK  | FK  | Nullable | Default | Description |
----|-------------------|------------|--------|-----|-----|----------|---------|------------- |
1   | id                | INT        | 10     | ✔ |     | ✘        |         | auto_increment |
2   | name              | VARCHAR    | 255    |     |     | ✘        |         |             |
3   | email             | VARCHAR    | 255    |     |     | ✘        |         |             |
4   | email_verified_at | TIMESTAMP  |        |     |     | ✔        |         |              |
5   | password          | VARCHAR    | 255    |     |     | ✘        |         |              |
6   | remember_token    | VARCHAR    | 100    |     |     | ✔        |         |              |
7   | created_at        | TIMESTAMP  |        |     |     | ✔        |         |              |
8   | updated_at        | TIMESTAMP  |        |     |     | ✔        |         |              |


# ローカル環境初期構築
## 初回構築
以下のコマンドを実行
```
make install
```

## 2回目以降のコンテナ起動
```
make up
```

## データベース初期化
```
make migrate-fresh
```

## キャッシュ削除
```
make cache-clear
```

## Composerオートロード設定再構築
```
make dump-autoload
```
