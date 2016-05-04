<?php
/**
 * Created by PhpStorm.
 * User: XiaoWei
 * Date: 2016/5/4 004
 * Time: 14:22
 */

require_once "../db/SqliteHelper.php";
require_once "../db/ContentValue.php";
require_once "../db/BillDao.php";
require_once "../model/Bill.php";
require_once "../model/ResponseJson.php";
require_once "../utils/JsonUtils.php";

$id = $_POST[BillDao::$COLUMN_ID];

$response = new ResponseJson();
if($id){
    $result = BillDao::getInstance()->deleteBill($id);
    if ($result) {
        $response->result = "删除账单成功";
    } else {
        $response->code = 2;
        $response->error = "删除账单失败";
    }
}else {
    $response->code = 1;
    $response->error = "请输入账单信息";
}

$code = JsonUtils::toJson($response);

echo $code;