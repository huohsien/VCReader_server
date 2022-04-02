<?php
// only two type of result: return verified user info or user info that is not verified yet
header('Content-type:application/json;charset=utf-8');
include_once("db.php");

$token = $_REQUEST["token"];
$nic_kname = $_REQUEST["nick_name"];
$timestamp = $_REQUEST["timestamp"];

$sql = "select * from User where signup_type = 'QQ' and token = '$token'";
$list_r = db_q($sql);
$rs = get_data($list_r);

if(empty($rs)) {

    // new QQ account. register data to mysql db
    $sql = "insert into User (nick_name, token, timestamp, signup_type) values ('$nick_name', '$token', '$timestamp', 'QQ')";
    $list_r = db_q($sql);
    $rs = get_data($list_r);
//    $user_id = mysql_insert_id();
//    $user_id = "$user_id";
    $verified = '0';
} else {
//    $user_id = $rs["id"];
    $verified = $rs["verified"];
}

$status["verified"] = $verified;
$status["nick_name"] = $nic_kname;
$status["token"] = $token;
$status["timestamp"] = $timestamp;
echo json_encode($status,true);

?>

