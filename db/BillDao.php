<?php

/**
 * Created by PhpStorm.
 * User: XiaoWei
 * Date: 2016/5/3 003
 * Time: 10:05
 */
class BillDao
{
    public static $Table_User = TABLE_BILL;
    public static $COLUMN_ID = "_id";
    public static $COLUMN_MONEY = "money";
    public static $COLUMN_PAYER_ID = "payerId";
    public static $COLUMN_RECORD_ID = "recordId";
    public static $COLUMN_BILL_TYPE = "billType";
    public static $COLUMN_IS_FINISH = "isFinish";
    public static $COLUMN_DATE = "date";
    public static $COLUMN_DESC = "desc";

    /** @var  BillDao */
    private static $sInstance;
    /** @var  SqliteHelper */
    private $mSqliteHelper;

    private function __construct()
    {
        require_once "SqliteHelper.php";
        require_once "ContentValue.php";
        $this->mSqliteHelper = new SqliteHelper();
    }

    public static function getInstance()
    {
        if (!isset(self::$sInstance)) {
            $c = __CLASS__;
            self::$sInstance = new $c;
        }
        return self::$sInstance;
    }

    /**
     * 添加账单
     * @param $bill Bill 账单数据
     * @return bool 添加结果
     */
    public function addBill($bill)
    {
        $value = new ContentValue();
        $value->put(self::$COLUMN_MONEY, $bill->money);
        $value->put(self::$COLUMN_PAYER_ID, $bill->payerId);
        $value->put(self::$COLUMN_RECORD_ID, $bill->recordId);
        $value->put(self::$COLUMN_BILL_TYPE, $bill->billType);
        $value->put(self::$COLUMN_IS_FINISH, $bill->isFinish);
        $value->put(self::$COLUMN_DATE, $bill->date);
        $value->put(self::$COLUMN_DESC, $bill->desc);

        return $this->mSqliteHelper->insert(self::$Table_User, $value);
    }

    public function getBills()
    {
        $result = $this->mSqliteHelper->query(self::$Table_User,null,null,null,null,null,"date desc");
        if($result){
            $bills = array();

            for ($i = 0; $i < count($result); $i++) {
                $row = $result[$i];

                $bill = new Bill();
                $bill->id = $row[self::$COLUMN_ID];
                $bill->money = $row[self::$COLUMN_MONEY];
                $bill->payerId = $row[self::$COLUMN_PAYER_ID];
                $bill->recordId = $row[self::$COLUMN_RECORD_ID];
                $bill->billType = $row[self::$COLUMN_BILL_TYPE];
                $bill->isFinish = $row[self::$COLUMN_IS_FINISH];
                $bill->date = $row[self::$COLUMN_DATE];
                $bill->desc = $row[self::$COLUMN_DESC];
            }
            return $bills;
        }
    }
}