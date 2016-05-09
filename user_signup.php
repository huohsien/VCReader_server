<?php
// need to call sms function for verification with the phone number submitted by app

header('Content-type:application/json;charset=utf-8');
include_once("db.php");

$account_name = $_REQUEST["account_name"];
$account_password = $_REQUEST["account_password"];
$nic_kname = $_REQUEST["nick_name"];
$phone_number = $_REQUEST["phone_number"];
$token = $_REQUEST["token"];
$timestamp = $_REQUEST["timestamp"];


// check availability for account name (openID for QQ. here openID is stored as token) and email
$sql = "select * from User where account_name='$account_name' or phone_number = '$phone_number'";
$list_r = db_q($sql);
$rs = get_data($list_r);
$verified = $rs["verified"];

if(empty($rs)) {

    // request to create a new user account

    // generate token for direct type of signing up
    $timestamp = strval(time());
    $token = sha1($account_name.$account_password.$timestamp);
    $status["token"] = $token;



    $sql = "insert into User (account_name,account_password,nick_name,phone_number,token,timestamp,signup_type) values ('$account_name','$account_password','$nick_name','$phone_number','$token','$timestamp','direct')";
    $list_r = db_q($sql);
    $user_id = mysql_insert_id()

} else {

    if ($verified == 0) {

        // generate token for direct type of signing up
        $timestamp = strval(time());
        $token = sha1($account_name.$account_password.$timestamp);
        $status["token"] = $token;
        $sql = "delete from User where account_name='$account_name' or phone_number = '$phone_number'";
        $list_r = db_q($sql);

        $sql = "insert into User (account_name,account_password,nick_name,phone_number,token,timestamp,signup_type) values ('$account_name','$account_password','$nick_name','$phone_number','$token','$timestamp','direct')";
        $list_r = db_q($sql);

    } else {

        $status["error"]["code"] = '103';
        $status["error"]["message"] = '账户或手机号已经存在';
        echo json_encode($status, true);
        exit();

    }
}

$status["account_name"] = $account_name;
$status["nick_name"] = $nic_kname;
$status["phone_number"] = $phone_number;
$status["token"] = $token;
$status["timestamp"] = $timestamp;
echo json_encode($status,true);

?>

