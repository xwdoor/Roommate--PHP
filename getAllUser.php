<?php
/**
 * 获取用户列表
 * User: XiaoWei
 * Date: 2016/5/3 003
 * Time: 10:18
 */
require_once "db/SqliteHelper.php";
require_once "db/ContentValue.php";
require_once "db/UserDao.php";
require_once "model/User.php";
require_once "model/ResponseJson.php";
require_once "utils/JsonUtils.php";

$response = new ResponseJson();
$users = UserDao::getInstance()->getAllUser();
if ($users) {
    $json = JsonUtils::toJson($users);
    $response->result = $json;
} else {
    $response->code = 1;
    $response->error = "获取信息错误";
}
//echo '获取用户列表';
$code = JsonUtils::toJson($response);
echo $code;