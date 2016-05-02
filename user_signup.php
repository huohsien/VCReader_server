<?php

// for QQ this api work not as real sign up but as store QQ data to VCReader's server. Sign-up part is done via QQ's own API and oAuth. Everytime using QQ to login this api got called.
header('Content-type:application/json;charset=utf-8');
include_once("db.php");

$account_name = $_REQUEST["account_name"];
$account_password = $_REQUEST["account_password"];
$nic_kname = $_REQUEST["nick_name"];
$email = $_REQUEST["email"];
$token = $_REQUEST["token"];
$timestamp = $_REQUEST["timestamp"];
$signup_type = $_REQUEST["signup_type"];

// check availability for account name (openID for QQ. here openID is stored as token) and email
$sql = "select * from User where account_name = '$account_name' or (token = '$token' and signup_type = 'QQ') or email = '$email'";
$list_r = db_q($sql);
$rs = get_data($list_r);

if(empty($rs)) {

    // request to create a new user account
    if ($signup_type != "QQ") {
        $token = ''; //make it empty. if the request is not for QQ type signing up, there shouldn't be a value for token yet.
    } else if (empty($token)){
        
        $status["error"]["code"] = '104';
        $status["error"]["message"] = 'openID(token) of the current QQ account is missing in the VCReader\'s server';
    }

    if(empty($token)) {
        // generate token for direct type of signing up
        $timestamp = strval(time());
        $token = sha1($account_name.$account_password.$timestamp);
        $status["token"] = $token;
    }

    $sql = "insert into User (account_name,account_password,nick_name,email,token,timestamp,signup_type) values ('$account_name','$account_password','$nick_name','$email','$token','$timestamp','$signup_type')";
    $list_r = db_q($sql);
    $user_id = mysql_insert_id();
    $status["user_id"] = $user_id;

} else {
    if ($signup_type == 'QQ') {
        $sql = "select * from User where token = '$token' and signup_type = 'QQ'";
        $list_r = db_q($sql);
        $rs = get_data($list_r);
        $user_id = $rs["id"];
        $status["user_id"] = $user_id;
    } else {
        $status["error"]["code"] = '103';
        $status["error"]["message"] = 'account or email already exists';
        echo json_encode($status,true);
        exit();
    }


}

$status["account_name"] = $account_name;
$status["nick_name"] = $nic_kname;
$status["email"] = $email;
$status["token"] = $token;
$status["timestamp"] = $timestamp;
$status["signup_type"] = $signup_type;
echo json_encode($status,true);

?>

