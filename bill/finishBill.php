<?php
/**
 * Created by PhpStorm.
 * User: XiaoWei
 * Date: 2016/5/5 005
 * Time: 13:55
 */
require_once "../db/SqliteHelper.php";
require_once "../db/ContentValue.php";
require_once "../db/BillDao.php";
require_once "../db/UserDao.php";
require_once "../model/Bill.php";
require_once "../model/ResponseJson.php";
require_once "../utils/JsonUtils.php";

$pwd = $_POST[UserDao::$COLUMN_PASSWORD];

$response = new ResponseJson();
if(strcmp($pwd,"xwdoor")===0){
    $result = BillDao::getInstance()->finishBill();
    if ($result) {
        $response->result = "结算账单成功";
    } else {
        $response->code = 2;
        $response->error = "结算账单失败";
    }
}else {
    $response->code = 1;
    $response->error = "请输入账单信息";
}

$code = JsonUtils::toJson($response);

echo $code;