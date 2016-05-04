<?php
/**
 * Created by PhpStorm.
 * User: XiaoWei
 * Date: 2016/5/3 003
 * Time: 16:19
 */

require_once "../db/SqliteHelper.php";
require_once "../db/ContentValue.php";
require_once "../db/BillDao.php";
require_once "../model/Bill.php";
require_once "../model/ResponseJson.php";
require_once "../utils/JsonUtils.php";

$payerId = $_GET[BillDao::$COLUMN_PAYER_ID];

$response = new ResponseJson();
$bills = BillDao::getInstance()->getBills($payerId);
if ($bills) {
    $json = JsonUtils::toJson($bills);
    $response->result = $json;
} else {
    $response->code = 1;
    $response->error = "获取信息错误";
}
//echo '获取用户列表';
$code = JsonUtils::toJson($response);
echo $code;
//echo JsonUtils::toJson($bills);