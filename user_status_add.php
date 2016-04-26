<?php
    header('Content-type:application/json;charset=utf-8');
    include_once("db.php");

    $book_name = $_REQUEST["book_name"];
    $current_reading_chapter = $_REQUEST["current_reading_chapter"];
    $current_reading_word = $_REQUEST["current_reading_word"];
    $timestamp = $_REQUEST["timestamp"];
    $sql = "select id from Book where book_name = '$book_name'";
    $list_r = db_q($sql);
    $rs = get_data($list_r);
    if(empty($rs)) {
        
        $status["error"]["code"] = '100';
        $status["error"]["message"] = 'book not found.';

        echo json_encode($status,true);

    } else {
        $book_id = $rs["id"];
        $sql = "insert into Reading_Status (user_id,book_id,current_reading_chapter,current_reading_word,timestamp) values (3,$book_id,$current_reading_chapter,$current_reading_word,$timestamp)";
        $list_r = db_q($sql);

        $status["success"]["code"] = '0';
        $status["success"]["message"] = 'OK';

        echo json_encode($status,true);

    }

?>
