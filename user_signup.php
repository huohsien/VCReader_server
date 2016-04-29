<?php
    header('Content-type:application/json;charset=utf-8');
    include_once("db.php");

    $account_name = $_REQUEST["account_name"];
    $account_password = $_REQUEST["account_password"];
    $nic_kname = $_REQUEST["nick_name"];
    $email = $_REQUEST["email"];
    $token = $_REQUEST["token"];
    $timestamp = $_REQUEST["timestamp"];
    $signup_type = $_REQUEST["signup_type"];

    $sql = "select * from User where account_name = '$account_name' or email = '$email'";
    $list_r = db_q($sql);
    $rs = get_data($list_r);
    if(empty($rs)) {

        if(empty($rs["token"])) {
            $timestamp = strval(time());
            $token = sha1($account_name.$account_password.$timestamp);
            $status["token"] = $token;
        }

        $sql = "insert into User (account_name,account_password,nick_name,email,token,timestamp,signup_type) values ('$account_name','$account_password','$nick_name','$email','$token','$timestamp','$signup_type')";
        $list_r = db_q($sql);
        $user_id = mysql_insert_id();
        $status["user_id"] = $user_id;

        echo json_encode($status,true);

    } else {
        if($signup_type == "QQ") {

            $status["success"]["code"] = '0';
            $status["success"]["message"] = 'OK';
            echo json_encode($status,true);
            exit();
        }
        $status["error"]["code"] = '103';
        $status["error"]["message"] = 'account name or email already exists';

        echo json_encode($status,true);
    }

?>

