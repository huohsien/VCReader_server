<?php

header('Content-type:application/json;charset=utf-8');
include_once("db.php");

$phone_number = $_REQUEST["phone_number"];
$code = $_REQUEST["code"];
$timestamp = $_REQUEST["timestamp"];


$sql = "select * from User where phone_number = '$phone_number'";
$list_r = db_q($sql);
$rs = get_data($list_r);
if (empty($rs)) {
    $status["error"]["code"] = '107';
    $status["error"]["message"] = ' phone number not found for verification';
    echo json_encode($status,true);
    exit();
}




// temp
$sql = "update User set verified = 1 where phone_number = '$phone_number'";
$list_r = db_q($sql);

$status["user_id"] = $rs["id"];



$status["nick_name"] = $nic_kname;
$status["token"] = $token;
$status["timestamp"] = $timestamp;
echo json_encode($status,true);

?>