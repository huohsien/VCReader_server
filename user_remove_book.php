<?php

header('Content-type:application/json;charset=utf-8');
include_once("db.php");

$token = $_REQUEST["token"];
$book_name = $_REQUEST["book_name"];


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

$sql = "delete from Ownership where book_id = '$book_id' and user_id = '#user_id'";
$list_r = db_q($sql);

$status["success"]["code"] = '4';
$status["success"]["message"] = 'User remove a book to his library successfully';

echo json_encode($status,true);

?>