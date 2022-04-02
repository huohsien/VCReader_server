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

$sql = "select * from Ownership where user_id = $user_id and  book_id =$book_id";
$list_r = db_q($sql);
$rs = get_data($list_r);

if(!empty($rs)) {

    $status["error"]["code"] = '113';
    $status["error"]["message"] = 'Try to add a new book that you already owned';

    echo json_encode($status,true);
    exit();
}

$sql = "insert into Ownership (user_id,book_id) values ($user_id,$book_id)";
$list_r = db_q($sql);

$status["success"]["code"] = '3';
$status["success"]["message"] = 'User add a book to his library successfully';

echo json_encode($status,true);

?>