<?php

header('Content-type:application/json;charset=utf-8');
include_once("db.php");

$email = $_REQUEST["email"];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Return Error - Invalid Email
    $status["error"]["code"] = '108';
    $status["error"]["message"] = 'Invalid email format';
    echo json_encode($status,true);
    exit();
}

$sql = "select * from User where email = '$email' and signup_type = 'direct'";
$list_r = db_q($sql);
$rs = get_data($list_r);
if (empty($rs)) {
    $status["error"]["code"] = '111';
    $status["error"]["message"] = 'Email not found';
    echo json_encode($status,true);
    exit();

} else {
    $name = $rs["account_name"];
    $password = $rs["account_password"];

    $to      = $email; // Send email to our user
    $subject = "『小说神器』"; // Give the email a subject
    $message = "您的账户名为:".$name."， 密码为:".$password."\r\n";

    mail($to, $subject, $message); // Send our email

}

$status["success"]["message"] = "OK";
$status["success"]["code"] = '0';
echo json_encode($status,true);

?>