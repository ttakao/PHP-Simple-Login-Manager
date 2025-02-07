<?php
require_once "includes/functions.php";

$token = isset($_GET['token']) ? $_GET['token'] : '';
$message = '';

if (!empty($token)) {
    // トークンをデータベースで検索
    $result = executeQuery("SELECT id, username, email FROM users WHERE verification_token = ? AND is_verified = 0", [$token]);
    
    if ($result->rowCount() > 0) {
        $user = $result->fetch(PDO::FETCH_ASSOC);
        
        // ユーザーアカウントを有効化
        executeQuery("UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?", [$user['id']]);
        
        $message = "メールアドレスが確認され、アカウントが有効化されました。<a href='login.php'>こちら</a>からログインしてください。";
    } else {
        $message = "無効または期限切れのトークンです。";
    }
} else {
    $message = "トークンが見つかりません。";
}

include "templates/header.htm";
?>

<h2>メールアドレス確認</h2>
<p><?php echo $message; ?></p>

<?php
include "templates/footer.htm";
?>
