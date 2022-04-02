<?php

header('Content-type:application/json;charset=utf-8');
include_once("db.php");

$token = $_REQUEST["token"];
$book_name = $_REQUEST["book_name"];
$current_reading_chapter = $_REQUEST["current_reading_chapter"];
$current_reading_word = $_REQUEST["current_reading_word"];
$timestamp = $_REQUEST["timestamp"];

$sql = "select id from Book where book_name = '$book_name'";
$list_r = db_q($sql);
$rs = get_data($list_r);

if(empty($rs)) {

    $status["error"]["code"] = '100';
    $status["error"]["message"] = 'Book not found.';

    echo json_encode($status,true);
    exit();
}
$book_id = $rs["id"];

$sql = "select id from User where token = '$token'";
$list_r = db_q($sql);
$rs = get_data($list_r);

if(empty($rs)) {

    $status["error"]["code"] = '105';
    $status["error"]["message"] = 'User not found.';

    echo json_encode($status,true);
    exit();
}
$user_id = $rs["id"];

$sql = "insert into Reading_Status (user_id,book_id,current_reading_chapter,current_reading_word,timestamp) values ($user_id,$book_id,$current_reading_chapter,$current_reading_word,$timestamp)";
$list_r = db_q($sql);

$status["success"]["code"] = '1';
$status["success"]["message"] = 'Inserted a reading status record successfully';

echo json_encode($status,true);

?>
