<?php
session_start();
require_once "includes/functions.php";

$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize_input($_POST["username"]);
    $password = sanitize_input($_POST["password"]);

    if (empty($username)) {
        $username_err = "ユーザー名を入力してください。";
    }
    if (empty($password)) {
        $password_err = "パスワードを入力してください。";
    }

    if (empty($username_err) && empty($password_err)) {
        $result = executeQuery("SELECT id, username, password FROM users WHERE username = ?", [$username]);
        if ($result->rowCount() == 1) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $row['id'];
                $_SESSION["username"] = $row['username'];
                redirect("index.php");
            } else {
                $password_err = "入力されたパスワードが正しくありません。";
            }
        } else {
            $username_err = "このユーザー名のアカウントが見つかりません。";
        }
    }
}

include "templates/header.htm";
include "templates/login.htm";
include "templates/footer.htm";
?>
