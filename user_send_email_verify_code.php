<?php

header('Content-type:application/json;charset=utf-8');
include_once("db.php");

$email = $_REQUEST["email"];
$token = $_REQUEST["token"];
$timestamp = $_REQUEST["timestamp"];

$token = mysql_escape_string($token);
$email = mysql_escape_string($email);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Return Error - Invalid Email
    $status["error"]["code"] = '108';
    $status["error"]["message"] = 'Invalid email format';
    echo json_encode($status,true);
    exit();
}

// check if the email already exists

$sql = "select * from User where email='$email'";
$list_r = db_q($sql);
$rs = get_data($list_r);

if (!empty($rs)) {
    $token_db = $rs["token"];
    $verified = $rs["verified"];

    if ($verified == 1) {

        $status["error"]["code"] = '112';
        $status["error"]["message"] = 'Email already exists';
        echo json_encode($status,true);
        exit();

    } else if ($token != $token_db) {

        $sql = "delete from User where email='$email'";
        $list_r = db_q($sql);
    }

}


$sql = "select * from User where token = '$token'";
$list_r = db_q($sql);
$rs = get_data($list_r);
if (empty($rs)) {
    $status["error"]["code"] = '107';
    $status["error"]["message"] = ' User not found for verification';
    echo json_encode($status,true);
    exit();

} else {

    $code = rand(100000,999999);

    $to      = $email; // Send email to our user
    $subject = "『小说神器』"; // Give the email a subject
    $message = "您的校验码为:".$code."\r\n";

    mail($to, $subject, $message); // Send our email
    $sql = "update User set email = '$email', verification_code = '$code', verification_code_timestamp = '$timestamp', verification_code = '$code' where token = '$token'";
    $list_r = db_q($sql);
}

$status["success"]["message"] = "OK";
$status["success"]["code"] = '0';
echo json_encode($status,true);

?>