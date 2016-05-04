<?php

/**
 * Created by PhpStorm.
 * User: XiaoWei
 * Date: 2016/5/3 003
 * Time: 10:05
 */
class BillDao
{
    public static $TABLE_BILL = TABLE_BILL;
    public static $TABLE_BILL_TYPE = TABLE_BILL_TYPE;
    public static $COLUMN_ID = "_id";
    public static $COLUMN_MONEY = "money";
    public static $COLUMN_PAYER_ID = "payerId";
    public static $COLUMN_RECORD_ID = "recordId";
    public static $COLUMN_BILL_TYPE = "billType";
    public static $COLUMN_IS_FINISH = "isFinish";
    public static $COLUMN_DATE = "date";
    public static $COLUMN_DESC = "desc";

    public static $COLUMN_TYPE_NAME = "typeName";

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

        return $this->mSqliteHelper->insert(self::$TABLE_BILL, $value);
    }

    private function parseBills($result)
    {
        if ($result) {
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

                $bills[$i] = $bill;
            }
            return $bills;
        }
        return null;
    }

    /**
     * 获取所有账单，按时间倒序排序
     * @param null|int $userId
     * @return array
     */
    public function getBills($userId = null)
    {
        if ($userId) {
            $whereClause = self::$COLUMN_PAYER_ID . "=:" . self::$COLUMN_PAYER_ID;
            $whereArgs = new ContentValue();
            $whereArgs->put(self::$COLUMN_PAYER_ID, $userId);
            $result = $this->mSqliteHelper->query(self::$TABLE_BILL, null, $whereClause, $whereArgs, null, null, "date desc");
        } else {
            $result = $this->mSqliteHelper->query(self::$TABLE_BILL, null, null, null, null, null, "date desc");
        }
        return $this->parseBills($result);
    }

    /**
     * 获取未结算账单账单，按payerId进行分组求和
     * @return array
     */
    public function getUnfinishedBills()
    {
        $columns = array(self::$COLUMN_PAYER_ID, "SUM(" . self::$COLUMN_MONEY . ") AS " . self::$COLUMN_MONEY);
        $whereClause = self::$COLUMN_IS_FINISH . "<=0";
        $result = $this->mSqliteHelper->query(self::$TABLE_BILL, $columns, $whereClause, null, self::$COLUMN_PAYER_ID, null, "date desc");

        return $this->parseBills($result);
    }

    /**
     * 获取账单类型
     * @return array
     */
    public function getBillType()
    {
        $result = $this->mSqliteHelper->query(self::$TABLE_BILL_TYPE, null, null, null);
        if ($result) {
            $billTypes = array();

            for ($i = 0; $i < count($result); $i++) {
                $row = $result[$i];

                $billType = new BillType($row[self::$COLUMN_ID], $row[self::$COLUMN_TYPE_NAME]);

                $billTypes[$i] = $billType;
            }
            return $billTypes;
        }
        return null;
    }

    /**
     * 更新账单
     * @param Bill $bill
     * @return bool
     */
    public function updateBill($bill)
    {
        $value = new ContentValue();
        $value->put(self::$COLUMN_MONEY, $bill->money);
        $value->put(self::$COLUMN_PAYER_ID, $bill->payerId);
        $value->put(self::$COLUMN_RECORD_ID, $bill->recordId);
        $value->put(self::$COLUMN_BILL_TYPE, $bill->billType);
        $value->put(self::$COLUMN_DATE, $bill->date);
        $value->put(self::$COLUMN_DESC, $bill->desc);

        $whereArgs = new ContentValue();
        $whereArgs->put(self::$COLUMN_ID, $bill->id);

        return $this->mSqliteHelper->update(self::$TABLE_BILL, $value, self::$COLUMN_ID . "=:" . self::$COLUMN_ID, $whereArgs);
    }
}