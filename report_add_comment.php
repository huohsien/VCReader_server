<?php

header('Content-type:application/json;charset=utf-8');
include_once("db.php");

$token = $_REQUEST["token"];
$comment = $_REQUEST["comment"];
$timestamp = $_REQUEST["timestamp"];

$sql = "select * from User where token = '$token' and verified = 1";
$list_r = db_q($sql);
$rs = get_data($list_r);
$user_id = $rs["id"];

if(empty($rs)) {

    $status["error"]["code"] = '105';
    $status["error"]["message"] = 'User not found.';

    echo json_encode($status,true);
    exit();

} else {

    $sql = "insert into Report (user_id, comment, timestamp) VALUES ($user_id, '$comment', '$timestamp')";
    $list_r = db_q($sql);
}
$response["token"] = $token;
$response["timestamp"] = $timestamp;

echo json_encode($response,true);

?>
