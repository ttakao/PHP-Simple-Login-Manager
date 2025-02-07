# PHPで使うシンプルな会員管理パッケージ

会員の登録、ログイン、ログオフ、パスワードのリセットだけのスケルトンに近いパッケージです。

## 背景
このパッケージはPHPを使ったWebアプリケーションで必ず必要となる、会員管理のテンプレートです。
なぜ、こういうものを作ったかというとMVCフレームワークの存在に疑問を感じたからです。
以下にどんなフレームワークにも見出す私個人としての疑問を列挙します。

1. ファイル数が多い。= ひとつひとつの機能を理解していられない
1. したがって、学習コストがバカにならない
1. Viewというが、書いてみれば変数入りhtmlのincludeに過ぎない
1. Modelというが、結局はSQL文のグルーピングに過ぎない。そんなにSQLを書くのがイヤならRDBを使わなければいい
1. レンタルサーバーではまだまだPHPが主流である
1. どんなシンプルなフレームワークも他パッケージに負けまいとして結局ブタのように太っていく
1. メールを送信するためにPHPMailerを使わないとトラブルが起きがち

## 構成
以上をふまえたシンプルな構成となっています。
PHPMailerがcomposerで導入されています。
環境による変数はすべてinclude/functions.php内のdefineステートメントに集約してあります。

会員登録にはregister.phpとverify_email.phpが使われ、htmlはtemplate/register.htmです。
登録するとメールにリンクが飛び、それを踏むことで正式ログインとなります。
ログインはlogin.phpで、htmlはlogin.htm
パスワードリセットはforgot_password.phpとreset_password.phpが使われ、htmlはforgot_password.htmとreset_password.htm
要求するとメールにリンクが飛び、それを踏むことでリセットとなります。
ログアウトはlogout.php


