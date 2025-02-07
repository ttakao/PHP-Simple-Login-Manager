<?php

require_once "includes/functions.php";
check_session();

// ユーザー情報を取得
$username = $_SESSION["username"];
$user_id = $_SESSION["id"];

// テンプレートに渡すデータ
$data = [
    'username' => $username
];

// テンプレートの読み込み
include "templates/header.htm";
include "templates/dashboard.htm";
include "templates/footer.htm";
?>
