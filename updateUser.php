<?php
/**
 * Created by PhpStorm.
 * User: XWdoor
 * Date: 2016/5/2
 * Time: 22:42
 */

require_once "db/SqliteHelper.php";
require_once "db/ContentValue.php";
require_once "db/UserDao.php";
require_once "model/User.php";
require_once "model/ResponseJson.php";
require_once "utils/JsonUtils.php";

$sqliteHelper = new SqliteHelper();
$values = new ContentValue();
$values->put(UserDao::$Column_Mail,"769765605@qq.com");
$r = $sqliteHelper->update(TABLE_USER,$values);
if($r){
    echo '更新成功';
}else{
    echo '更新失败';
}