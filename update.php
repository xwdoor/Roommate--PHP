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
$appInfo->versionCode = 4;
$appInfo->versionName = "1.1.2";
$appInfo->description = "1.付款人默认为当前登录用户；\n2.结算账单时给予提示；\n3.优化账单列表的日期显示bug";
$appInfo->downloadUrl = "http://xwdoor.net/roommate/download.php?version=" . $appInfo->versionName;

$response = new ResponseJson();
$response->result = JsonUtils::toJson($appInfo);
echo JsonUtils::toJson($response);
