<?php
/**
 * Created by PhpStorm.
 * User: XiaoWei
 * Date: 2016/5/4 004
 * Time: 11:17
 */
require_once "../db/SqliteHelper.php";
require_once "../db/ContentValue.php";
require_once "../db/BillDao.php";
require_once "../model/Bill.php";
require_once "../model/ResponseJson.php";
require_once "../utils/JsonUtils.php";

$response = new ResponseJson();
$bills = BillDao::getInstance()->getUnfinishedBills();
if ($bills) {
    $json = JsonUtils::toJson($bills);
    $response->result = $json;
} else {
//    $response->code = 1;
//    $response->error = "获取账单信息错误";
    //可以是空账单
    $response->code = 0;
    $response->error = "没有该账单信息";
    $response->result = null;
}
//echo '获取用户列表';
$code = JsonUtils::toJson($response);
echo $code;