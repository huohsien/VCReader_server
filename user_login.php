<?php

    header('Content-type:application/json;charset=utf-8');
    include_once("db.php");
    $account_name = $_REQUEST["account_name"];
    $account_password = $_REQUEST["account_password"];

if (empty($account_name) || empty($account_password)) {
        $response["error"]["code"] = '102';
        $response["error"]["message"] = "Incorrect account name or password";
        echo json_encode($response,true);
        exit();
    }
    $sql = "select * from User where account_name = '$account_name' and account_password  = '$account_password'";
    $list_r = db_q($sql);
    $rs = get_data($list_r);
    if (empty($rs)) {
        $response["error"]["code"] = '102';
        $response["error"]["message"] = "Incorrect account name or password";
    } else {
        $response["nick_name"] = $rs["nick_name"];
        $response["email"] = $rs["email"];
        $response["token"] = $rs["token"];
        $response["timestamp"] = $rs["timestamp"];
        $response["user_id"] = $rs["id"];
        $response["signup_type"] = $rs["signup_type"];
    }
    echo json_encode($response,true);
?>
