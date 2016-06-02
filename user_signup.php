<?php
// need to call sms function for verification with the phone number submitted by app

header('Content-type:application/json;charset=utf-8');
include_once("db.php");

$account_name = $_REQUEST["account_name"];
$account_password = $_REQUEST["account_password"];
$nic_kname = $_REQUEST["nick_name"];
$timestamp = $_REQUEST["timestamp"];


// check availability for account name
$sql = "select * from User where account_name='$account_name'";
$list_r = db_q($sql);
$rs = get_data($list_r);
$verified = $rs["verified"];

if(empty($rs)) {
    
    // request to create a new user account

    // generate token for direct type of signing up
    $token = sha1($account_name.$account_password.$timestamp);
    $status["token"] = $token;



    $sql = "insert into User (account_name,account_password,nick_name,token,timestamp,signup_type) values ('$account_name','$account_password','$nick_name','$token','$timestamp','direct')";
    $list_r = db_q($sql);


} else {

    if ($verified == 0) {

        // generate token for direct type of signing up
        $token = sha1($account_name.$account_password.$timestamp);
        $status["token"] = $token;

        //delete the record with the same account name that is not phone verified. Phone verified accounts are the only "real" account. Others are just for temporary use.
        $sql = "delete from User where account_name='$account_name'";
        $list_r = db_q($sql);

        //create new account
        $sql = "insert into User (account_name,account_password,nick_name,token,timestamp,signup_type) values ('$account_name','$account_password','$nick_name','$token','$timestamp','direct')";
        $list_r = db_q($sql);


    } else {

        $status["error"]["code"] = '103';
        $status["error"]["message"] = '账户已经存在';
        echo json_encode($status, true);
        exit();

    }
}

$status["account_name"] = $account_name;
$status["nick_name"] = $nic_kname;
$status["token"] = $token;
$status["timestamp"] = $timestamp;
echo json_encode($status,true);

?>

