# 設計

- 登録画面 register.php
  - フォームバリデーション
    - email
    - password
      - 8 文字
      - 小文字英数
  - 登録処理
    - バリデーションクリア
    - password ハッシュ化
    - DB ステータス`検証中`登録
    - 検証メール送信
    - 送信メールのリンクをクリックしてください
    - 検証メールクリック
    - DB ステータス`登録`登録
- ログイン画面 login.php
  - フォームバリデーション
  - ログイン成功
    - cookie にセッション発行
    - ユーザー一覧画面表示
  - ログイン失敗
    - エラーメッセージスプラッシュ
    - ログイン画面表示
- 自動ログイン
  - cookie でログイン
- 一覧画面 list.php
  - cookie でログイン
    - 認証通ったら一覧画面を表示
    - 認証通らなかったらログイン画面
