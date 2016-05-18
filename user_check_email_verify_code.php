<?php

header('Content-type:application/json;charset=utf-8');
include_once("db.php");

$code = $_REQUEST["code"];
$token = $_REQUEST["token"];
$timestamp = $_REQUEST["timestamp"];

$sql = "select * from User where token = '$token'";
$list_r = db_q($sql);
$rs = get_data($list_r);
if (empty($rs)) {
    $status["error"]["code"] = '107';
    $status["error"]["message"] = ' User not found for verification';
    echo json_encode($status,true);
    exit();

} else {
    $code_db = $rs["verification_code"];
    $timestamp_db = $rs["verification_code_timestamp"];
    if ( (intval($timestamp) - intval($timestamp_db)) < 10 * 60 * 1000 ) {

        if (strcmp($code, $code_db) == 0) {

            $sql = "update User set verified = 1 where token = '$token'";
            $list_r = db_q($sql);
            $status["success"]["message"] = "OK";
            $status["success"]["code"] = '0';

        } else {
            $status["error"]["code"] = '109';
            $status["error"]["message"] = "Failed to verify email";
        }

    } else {

        $status["error"]["code"] = '110';
        $status["error"]["message"] = "Email verification code expired";
    }


}


echo json_encode($status,true);

?>