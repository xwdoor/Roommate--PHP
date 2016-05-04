<?php
/**
 * Created by PhpStorm.
 * User: XiaoWei
 * Date: 2016/5/4 004
 * Time: 9:55
 */

require_once "../db/SqliteHelper.php";
require_once "../db/ContentValue.php";
require_once "../db/BillDao.php";
require_once "../model/Bill.php";
require_once "../model/BillType.php";
require_once "../model/ResponseJson.php";
require_once "../utils/JsonUtils.php";

$response = new ResponseJson();
$billTypes = BillDao::getInstance()->getBillType();
if ($billTypes) {
    $json = JsonUtils::toJson($billTypes);
    $response->result = $json;
} else {
    $response->code = 1;
    $response->error = "获取账单信息错误";
}
//echo '获取用户列表';
$code = JsonUtils::toJson($response);
echo $code;