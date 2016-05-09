<?php

header('Content-type:application/json;charset=utf-8');
include_once("db.php");

$nic_kname = $_REQUEST["nick_name"];
$token = $_REQUEST["token"];
$timestamp = $_REQUEST["timestamp"];


$sql = "select * from User where signup_type = 'QQ' and token = '$token'";
$list_r = db_q($sql);
$rs = get_data($list_r);
$verified = $rs["verified"];
$status["phone_number"] = '';
$user_id = $rs["id"];

if(empty($rs)) {
    // new QQ account. register data to mysql db
    $sql = "insert into User (nick_name,token,timestamp,signup_type) values ('$nick_name','$token','$timestamp','QQ')";
    $list_r = db_q($sql);

} else if ($verified == 1) {
        // login verified QQ account
        $status["user_id"] = $user_id;
        $status["phone_number"] = $rs["phone_number"];
}
$status["user_id"] = $user_id;
$status["nick_name"] = $nic_kname;
$status["token"] = $token;
$status["timestamp"] = $timestamp;
echo json_encode($status,true);

?>

