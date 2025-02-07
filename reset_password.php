<?php
require_once "includes/functions.php";

$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST["token"];
    $new_password = sanitize_input($_POST["new_password"]);
    $confirm_password = sanitize_input($_POST["confirm_password"]);
    
    // パスワードの検証
    if (empty($new_password)) {
        $new_password_err = "新しいパスワードを入力してください。";
    } elseif (strlen($new_password) < 6) {
        $new_password_err = "パスワードは少なくとも6文字以上である必要があります。";
    }
    
    if (empty($confirm_password)) {
        $confirm_password_err = "パスワードを確認してください。";
    } else {
        if ($new_password != $confirm_password) {
            $confirm_password_err = "パスワードが一致しません。";
        }
    }
    
    // エラーがなければパスワードを更新
    if (empty($new_password_err) && empty($confirm_password_err)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $result = executeQuery("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ? AND reset_token_expiry > NOW()", 
                               [$hashed_password, $token]);
        
        if ($result->rowCount() > 0) {
            echo "パスワードが正常に更新されました。";
            // ログインページへリダイレクト
            redirect("login.php");
        } else {
            echo "パスワードの更新に失敗しました。トークンが無効か期限切れの可能性があります。";
        }
    }
}

include "templates/header.htm";
include "templates/reset_password.htm";
include "templates/footer.htm";
?>
