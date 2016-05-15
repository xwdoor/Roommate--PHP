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
$appInfo->versionCode = 5;
$appInfo->versionName = "1.1.3";
$appInfo->description = "1.个人中心显示个人账单\n2.优化账单显示\n3.解决闪退问题";
$appInfo->downloadUrl = "http://xwdoor.net/roommate/download.php?version=" . $appInfo->versionName;

$response = new ResponseJson();
$response->result = JsonUtils::toJson($appInfo);
echo JsonUtils::toJson($response);
