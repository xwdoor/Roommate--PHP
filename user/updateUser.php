<?php
/**
 * Created by PhpStorm.
 * User: XWdoor
 * Date: 2016/5/2
 * Time: 22:42
 */

require_once "../db/SqliteHelper.php";
require_once "../db/ContentValue.php";
require_once "../db/UserDao.php";
require_once "../model/User.php";
require_once "../model/ResponseJson.php";
require_once "../utils/JsonUtils.php";

$sqliteHelper = new SqliteHelper();
$values = new ContentValue();
$values->put(UserDao::$COLUMN_MAIL, "769765605@qq.com");
$r = $sqliteHelper->update(TABLE_USER, $values);
if ($r) {
    echo '更新成功';
} else {
    echo '更新失败';
}

$response = new ResponseJson();

$id = $_GET[UserDao::$COLUMN_ID];
$realName = $_GET[UserDao::$COLUMN_REAL_NAME];
$mail = $_GET[UserDao::$COLUMN_MAIL];
$phone = $_GET[UserDao::$COLUMN_PHONE];
$password = $_GET[UserDao::$COLUMN_PASSWORD];

if ($id && $password) {//id和密码不能为空
    if ($realName || $mail || $phone) {//有一个不为空即可
        $user = new User($realName, $mail, $phone, $password);
        $user->id = $id;
        $result = UserDao::getInstance()->updateUser($user);
        if ($result) {
            $response->result = "更新用户信息成功";
        } else {
            $response->code = 3;
            $response->error = "更新用户信息失败";
        }
    } else {
        $response->code = 2;
        $response->error = "请输入需要更新的信息";
    }
} else {
    $response->code = 1;
    $response->error = "请输入验证信息";
}

$code = JsonUtils::toJson($response);

echo $code;