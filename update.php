<?php
/**
 * Created by PhpStorm.
 * User: XiaoWei
 * Date: 2016/5/6 006
 * Time: 8:59
 */

require_once "model/AppInfo.php";
require_once "model/ResponseJson.php";
require_once "utils/JsonUtils.php";

$appInfo = new AppInfo();
$appInfo->versionCode = 6;
$appInfo->versionName = "1.2.0";
$appInfo->description = "1.增加修改个人信息的功能\n2.优化网络错误的处理\n3.已知Bug优化";
$appInfo->downloadUrl = "http://xwdoor.net/roommate/download.php?version=" . $appInfo->versionName;

$response = new ResponseJson();
$response->result = JsonUtils::toJson($appInfo);
echo JsonUtils::toJson($response);
