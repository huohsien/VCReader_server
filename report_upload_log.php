<?php

$target_dir = "uploaded_logs/";

$target_file = $target_dir .'log_'.basename($_FILES['upfile']['name']).'.txt';



// Check if file already exists
if (file_exists($target_file)) {
    $status["error"]["code"] = "300";
    $status["error"]["message"] = "Try to upload a log file that already exists";
    echo json_encode($status,true);
    exit();
}
// Check file size
if ($_FILES['upfile']['size'] > 10000000) {
    $status["error"]["code"] = "301";
    $status["error"]["message"] = "Try to upload a log file that is larger than the limit";
    echo json_encode($status,true);
    exit();
}

if (move_uploaded_file($_FILES['upfile']['tmp_name'], $target_file)) {
    $status["success"]["code"] = "101";
    $status["success"]["message"] = "Upload file successfully";
    echo json_encode($status,true);
} else {
    $status["error"]["code"] = "302";
    $status["error"]["message"] = "Failed to upload file";
    echo json_encode($status,true);
    exit();
}

?>