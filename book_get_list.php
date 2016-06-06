<?php

header('Content-type:application/json;charset=utf-8');
include_once("db.php");
$token =$_REQUEST["token"];

if (empty($token)) {

    $sql = "select book_name, content_filename, cover_image_filename, timestamp from Book  order by id desc";
    $result = db_q($sql);

} else {
    $sql = "select b.book_name, b.content_filename, b.cover_image_filename, b.timestamp from Book as b join Ownership as o on o.book_id = b.id join User as u on u.id = o.user_id where u.token = '$token' order by b.id desc";
    $result = db_q($sql);

}

$directory = './books/';
$output = array();
$idx = 0;

while ($row = mysql_fetch_array($result, MYSQL_BOTH)) {
    if (file_exists($directory.$row[1]) && file_exists($directory.$row[2])) {

        $output[$idx]["book_name"] = $row[0];
        $output[$idx]["content_filename"] = $directory.$row[1];
        $output[$idx]["cover_image_filename"] = $directory.$row[2];
        $output[$idx]["timestamp"] = $row[3];

        $idx++;
    }
}

echo json_encode($output,true);

?>