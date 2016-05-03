<?php
/**
 * Created by PhpStorm.
 * User: XWdoor
 * Date: 2016/5/1
 * Time: 15:00
 */

require_once "../db/SqliteHelper.php";
require_once "../db/ContentValue.php";
require_once "../db/UserDao.php";
require_once "../model/User.php";
require_once "../model/ResponseJson.php";
require_once "../utils/JsonUtils.php";

$realName = $_POST[UserDao::$COLUMN_REAL_NAME];
$phone = $_POST[UserDao::$COLUMN_PHONE];
$mail = $_POST[UserDao::$COLUMN_MAIL];
$password = $_POST[UserDao::$COLUMN_PASSWORD];

$response = new ResponseJson();
if ($realName && $phone && $mail && $password) {
    $user = new User($realName,$phone,$mail,$password);
    
    $result = UserDao::getInstance()->addUser($user);
    if($result){
        $response->result = "添加成功";
    }else{
        $response->code = 2;
        $response->error = "添加失败";
    }
} else {
    $response->code = 1;
    $response->error = "请输入用户信息";
}

$code = JsonUtils::toJson($response);

echo $code;