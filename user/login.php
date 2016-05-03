<?php


require_once "../db/SqliteHelper.php";
require_once "../db/ContentValue.php";
require_once "../db/UserDao.php";
require_once "../model/User.php";
require_once "../model/ResponseJson.php";
require_once "../utils/JsonUtils.php";

//$user = UserDao::getInstance()->getUser("18684033888","xwdoor");
//$code = JsonUtils::toJson($user);
//var_dump($code);
//$array = JsonUtils::fromJson($code,true);
//var_dump($array);

$account = $_GET['loginName'];
$password = $_GET['pwd'];
//echo $account . $password;

$response = new ResponseJson();
if ($account && $password) {
    $user = UserDao::getInstance()->getUser($account, $password);
    if ($user) {
        $json = JsonUtils::toJson($user);
        $response->result = $json;
    } else {
        $response->code = 1;
        $response->error = "账号密码错误";
    }
} else {
    $response->code = 2;
    $response->error = "请输入账号密码";
}

$result = JsonUtils::toJson($response);
echo $result;
