<?php
require_once "includes/functions.php";

$username = $email = $password = "";
$username_err = $email_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize_input($_POST["username"]);
    $email = sanitize_input($_POST["email"]);
    $password = sanitize_input($_POST["password"]);

    // ユーザー名の確認
    if (empty($username)) {
        $username_err = "ユーザー名を入力してください。";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $username_err = "ユーザー名は英数字とアンダースコアのみ使用可能です。";
    } else {
        $result = executeQuery("SELECT id FROM users WHERE username = ?", [$username]);
        if ($result->rowCount() > 0) {
            $username_err = "このユーザー名は既に使用されています。";
        }
    }

    // メールアドレスの確認
    if (empty($email)) {
        $email_err = "メールアドレスを入力してください。";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "有効なメールアドレスを入力してください。";
    } else {
        $result = executeQuery("SELECT id FROM users WHERE email = ?", [$email]);
        if ($result->rowCount() > 0) {
            $email_err = "このメールアドレスは既に使用されています。";
        }
    }

    // パスワードの確認
    if (empty($password)) {
        $password_err = "パスワードを入力してください。";
    } elseif (strlen($password) < 6) {
        $password_err = "パスワードは6文字以上である必要があります。";
    }

    // エラーがなければ、ユーザーを仮登録
    if (empty($username_err) && empty($email_err) && empty($password_err)) {
        // 一意のトークンを生成
        $token = bin2hex(random_bytes(16));
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // ユーザー情報とトークンをデータベースに保存（仮登録状態）
        executeQuery("INSERT INTO users (username, email, password, verification_token, is_verified) VALUES (?, ?, ?, ?, 0)", [$username, $email, $hashed_password, $token]);

        // 確認メールを送信
        $verification_link = BASE_URL . "verify_email.php?token=" . $token;
        $to = $email;
        $subject = "メールアドレス確認";
        $message = "以下のリンクをクリックしてメールアドレスを確認してください：\n\n" . $verification_link;
        send_mail($to, $subject, $message);

        // 登録完了メッセージ
        echo "確認メールを送信しました。メールをご確認ください。";
    }
}

include "templates/header.htm";
include "templates/register.htm";
include "templates/footer.htm";
?>
