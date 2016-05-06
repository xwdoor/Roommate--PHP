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
$appInfo->versionCode = 2;
$appInfo->versionName = "1.1.0";
$appInfo->description = "1.增加新版本检测.\n2.账单金额不超过6位数.\n3.修复已知Bug.";
$appInfo->downloadUrl = "http://xwdoor.net/roommate/download.php?version=" . $appInfo->versionName;

$response = new ResponseJson();
$response->result = JsonUtils::toJson($appInfo);
echo JsonUtils::toJson($response);
