<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

define('BASE_URL', '');
define('MAIL_HOST', '');
define('MAIL_USER', '');
define('MAIL_PASSWORD','');
define('MAIL_FROM_ADDRESS','');
define('MAIL_FROM_NAME','');

define('DB_HOST','localhost');
define('DB_NAME','');
define('DB_USER','');
define('DB_PASSWORD', '');

require_once 'db_functions.php';

mb_language("Japanese");
mb_internal_encoding("UTF-8");


// Instantiation and passing `true` enables exceptions
$mailer = new PHPMailer(true);

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function check_session(){
    session_start();

    // ユーザーがログインしていない場合、ログインページにリダイレクト
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        redirect("login.php");
        exit;
    }
}


function send_mail($to,$subject,$body) {

    global $mailer;
    //php.iniの設定がJapaneseとUTF-8

    $mailer -> Charset = "UTF-8";
    $mailer -> isSMTP();
    $mailer -> Host = MAIL_HOST;
    $mailer -> SMTPAuth = true;
    $mailer -> Username = MAIL_USER;
    $mailer -> Password = MAIL_PASSWORD;
    $mailer -> Port = 587; 
    $mailer -> isHTML(false);
    $mailer -> setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);

    $mailer -> addAddress($to);
    $mailer -> Subject = mb_encode_mimeheader($subject);
    $mailer -> Body = $body;
    
    // メール送信します。
    if (!$mailer -> send()){
        echo 'メッセージの送信失敗';
        echo 'Error:' . $mailer->ErrorInfo;

    } else {
        echo 'メッセージ送信成功';
    }
}

?>