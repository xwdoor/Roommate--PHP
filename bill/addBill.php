<?php
/**
 * Created by PhpStorm.
 * User: XiaoWei
 * Date: 2016/5/3 003
 * Time: 15:24
 */

require_once "../db/SqliteHelper.php";
require_once "../db/ContentValue.php";
require_once "../db/BillDao.php";
require_once "../model/Bill.php";
require_once "../model/ResponseJson.php";
require_once "../utils/JsonUtils.php";

$money = $_POST[BillDao::$COLUMN_MONEY];
$payerId = $_POST[BillDao::$COLUMN_PAYER_ID];
$recordId = $_POST[BillDao::$COLUMN_RECORD_ID];
$billType = $_POST[BillDao::$COLUMN_BILL_TYPE];
$date = $_POST[BillDao::$COLUMN_DATE];
$desc = $_POST[BillDao::$COLUMN_DESC];

$response = new ResponseJson();

if ($money && $payerId >= 0 && $recordId >= 0 && $billType >= 0 && $date) {
    $bill = new Bill($money, $payerId, $recordId, $billType, $date, $desc);
    $result = BillDao::getInstance()->addBill($bill);
    if ($result) {
        $response->result = "添加账单成功";
    } else {
        $response->code = 2;
        $response->error = "添加账单失败";
    }
} else {
    $response->code = 1;
    $response->error = "请输入账单信息";
}

//$bill = new Bill(12, 1, 1, 1, '2016-05-03', '备注');
//var_dump($bill->money);
//$result = BillDao::getInstance()->addBill($bill);
//if ($result) {
//    $response->result = "添加成功";
//} else {
//    $response->code = 2;
//    $response->error = "添加失败";
////    $response->result = $bill;
//}

$code = JsonUtils::toJson($response);

echo $code;