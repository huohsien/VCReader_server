<?php
/**
 * Created by PhpStorm.
 * User: huohsien
 * Date: 5/2/16
 * Time: 6:16 PM
 */
header('Content-type:application/json;charset=utf-8');
include_once("db.php");
$user_id =$_REQUEST["user_id"];

if (empty($user_id)) {
    $output["error"]["code"] = '200';
    $output["error"]["message"] =  'Parameters are missing';
    echo json_encode($output,true);
    exit();
}

$sql = "select b.book_name, b.content_filename, b.cover_image_filename, b.timestamp from Book as b join Ownership as o on o.book_id = b.id join User as u on u.id = o.user_id where u.id = $user_id";
$result = db_q($sql);

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