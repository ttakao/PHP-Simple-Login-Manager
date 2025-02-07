<?php
require_once "includes/functions.php";

$email = $email_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitize_input($_POST["email"]);
    
    if (empty($email)) {
        $email_err = "メールアドレスを入力してください。";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "有効なメールアドレスを入力してください。";
    } else {
        $result = executeQuery("SELECT id FROM users WHERE email = ?", [$email]);
        if ($result->rowCount() == 1) {
            $token = bin2hex(random_bytes(50));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            executeQuery("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?", 
                         [$token, $expiry, $email]);
            
            // パスワードリセットリンクを生成
            $reset_link = BASE_URL . "reset_password.php?token=" . $token;
                
            $to = $email;
            $subject = "パスワードリセットのリクエスト";
            $message = "以下のリンクからパスワードをリセットしてください：\n\n" . $reset_link;
            
            send_mail($to, $subject, $message);
            
            echo "パスワードリセットリンクをメールで送信しました。メールをご確認ください。";
        } else {
            $email_err = "このメールアドレスは登録されていません。";
        }
    }
}

include "templates/header.htm";
include "templates/forgot_password.htm";
include "templates/footer.htm";
?>
