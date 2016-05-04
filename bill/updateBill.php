<?php
/**
 * Created by PhpStorm.
 * User: XiaoWei
 * Date: 2016/5/4 004
 * Time: 14:09
 */

require_once "../db/SqliteHelper.php";
require_once "../db/ContentValue.php";
require_once "../db/BillDao.php";
require_once "../model/Bill.php";
require_once "../model/ResponseJson.php";
require_once "../utils/JsonUtils.php";

$id = $_POST[BillDao::$COLUMN_ID];
$money = $_POST[BillDao::$COLUMN_MONEY];
$payerId = $_POST[BillDao::$COLUMN_PAYER_ID];
$recordId = $_POST[BillDao::$COLUMN_RECORD_ID];
$billType = $_POST[BillDao::$COLUMN_BILL_TYPE];
$date = $_POST[BillDao::$COLUMN_DATE];
$desc = $_POST[BillDao::$COLUMN_DESC];

$response = new ResponseJson();
if ($id) {
    $bill = new Bill($money, $payerId, $recordId, $billType, $date, $desc);
    $bill->id = $id;
    $result = BillDao::getInstance()->updateBill($bill);
    if ($result) {
        $response->result = "更新账单成功";
    } else {
        $response->code = 2;
        $response->error = "更新账单失败";
    }
} else {
    $response->code = 1;
    $response->error = "请输入账单信息";
}

$code = JsonUtils::toJson($response);

echo $code;