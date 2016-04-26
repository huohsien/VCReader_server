<?php
    header('Content-type:application/json;charset=utf-8');
    include_once("db.php");

    $book_name = $_REQUEST["book_name"];
    $sql = "select id from Book where book_name = '$book_name'";
    $list_r = db_q($sql);
    $rs = get_data($list_r);

    if(empty($rs)) {
        
        $status["error"]["code"] = '100';
        $status["error"]["message"] = 'book not found.';

        echo json_encode($status,true);
        exit();
    }

    $book_id = $rs["id"];
    $sql = "select * from Reading_Status where book_id = $book_id order by TIMESTAMP DESC";
    $list_r = db_q($sql);
    $rs = get_data($list_r);
    
    if (empty($rs)) {
        $status["error"]["code"] = '101';
        $status["error"]["message"] = "user's reading status for the book is not found";
        
        echo json_encode($status,true);
        exit();
   
    }
    $status["chapter"] = $rs["current_reading_chapter"];
    $status["word"] = $rs["current_reading_word"];
    $status["timestamp"] = $rs["timestamp"];
    echo json_encode($status,true);
?>
