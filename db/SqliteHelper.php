<?php

/**
 * Sqlite数据库操作
 * User: XWdoor
 * Date: 2016/4/27
 * Time: 19:44
 */
define('DATABASE_NAME', dirname(__FILE__).'/Roommate.sqlite');
define("TABLE_BILL", "R_BillData");
define("TABLE_USER", "R_User");
define("TABLE_BILL_TYPE", "R_BillType");

class SqliteHelper
{
    /** @var PDO */
    private $mPdo;
    /** @var array */
    private $tables;

    /**
     * SqliteHelper constructor.
     */
    public function __construct()
    {
        require_once "ContentValue.php";
        $this->mPdo = new PDO('sqlite:' . DATABASE_NAME);

        $this->tables = array();
        $this->initDatabaseFile();
    }

    private function initDatabaseFile()
    {
        $this->findTables();
        $this->createTables();
    }

    /** 查找数据库中所有表格 */
    private function findTables()
    {
        $sql = "SELECT name FROM SQLITE_MASTER WHERE type='table' ORDER BY name";

        $result = $this->mPdo->query($sql);
        if ($result) {
            $index = 0;
            $result->setFetchMode(PDO::FETCH_BOTH);
            while ($row = $result->fetch()) {
                $this->tables[$index] = $row[0];
                $index++;
            }
        }
//        var_dump($this->tables);
    }

    /** 创建表格 */
    private function createTables()
    {
        //创建账单表
        $createTableBill = "create table " . TABLE_BILL . " (" .
            "_id integer primary key autoincrement, " .
            "money decimal(10,2), " .
            "payerId integer," .
            "recordId integer," .
            "billType integer," .
            "isFinish integer," .
            "date varchar(20)," .
            "desc text)";
        //如果不存在，则创建
        if (!in_array(TABLE_BILL, $this->tables) && !$this->isTableExist(TABLE_BILL)) {
            $this->mPdo->exec($createTableBill);

//            $sql = "INSERT INTO R_BillData(money,payerId,recordId,billType,isFinish,date,desc) VALUES (23.5,1,1,1,0,'2015-05-03','备注信息')";
//            $this->mPdo->exec($sql);
        }

        //创建用户表
        $createUser =
            "create table " . TABLE_USER . " (" .
            "_id integer primary key autoincrement, " .
            "userName varchar(20)," .
            "realName varchar(20)," .
            "phone varchar(20)," .
            "password varchar(20)," .
            "mail varchar(20))";

        if (!in_array(TABLE_USER, $this->tables) && !$this->isTableExist(TABLE_USER)) {
            $this->mPdo->exec($createUser);
//            var_dump("create table user");
            $sql = "INSERT INTO R_User (userName, realName, phone, password, mail) VALUES ('xwdoor', '肖威', '18684033888', 'xwdoor', 'xwdoor@126.com')";
            $this->mPdo->exec($sql);
        }

        //创建账单类型表
        $createBillType =
            "create table " . TABLE_BILL_TYPE . " (" .
            "_id integer primary key autoincrement, " .
            "typeName varchar(20))";
        if (!in_array(TABLE_BILL_TYPE, $this->tables) && !$this->isTableExist(TABLE_BILL_TYPE)) {
            $this->mPdo->exec($createBillType);

            $sql = "INSERT INTO R_BILLTYPE(typeName) VALUES('市场')";
            $this->mPdo->exec($sql);
            $sql = "INSERT INTO R_BILLTYPE(typeName) VALUES('超市')";
            $this->mPdo->exec($sql);
            $sql = "INSERT INTO R_BILLTYPE(typeName) VALUES('水费')";
            $this->mPdo->exec($sql);
            $sql = "INSERT INTO R_BILLTYPE(typeName) VALUES('电费')";
            $this->mPdo->exec($sql);
            $sql = "INSERT INTO R_BILLTYPE(typeName) VALUES('气费')";
            $this->mPdo->exec($sql);
            $sql = "INSERT INTO R_BILLTYPE(typeName) VALUES('其他')";
            $this->mPdo->exec($sql);
        }
    }

    /**
     * 判断表名是否存在
     * @param string $tableName 表名
     * @return bool 是否存在
     */
    private function isTableExist($tableName)
    {
        $sql = "SELECT count(*) FROM sqlite_master WHERE type='table' AND name='$tableName'";
        $stmt = $this->mPdo->query($sql);
        $result = $stmt->fetchAll();
        return $result[0][0];
    }

    /**
     * 添加列名到Sql语句中
     * @param $columns array
     * @param $bindValue bool 是否参数化列名
     * @return string
     */
    private function appendColumn($columns, $bindValue = false)
    {
        $string = '';
        for ($i = 0; $i < count($columns); $i++) {
            if ($columns[$i]) {
                if ($bindValue) {
                    $string .= ($i > 0) ? ',:' : ':';
                } else {
                    $string .= ($i > 0) ? ',' : '';
                }
                $string .= $columns[$i];
            }
        }
        $string .= ' ';
        return $string;
    }

    /**
     * 构建参数化SQL语句PDOStatement
     * @param string $sql sql语句
     * @param ContentValue $values sql语句中的参数
     * @return PDOStatement
     */
    private function getStatement($sql, $values)
    {
        $stmt = $this->mPdo->prepare($sql);
        if ($values) {
            $this->buildParams($stmt, $values);
        }
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        //不能close，不然$result不能读取
//            $stmt->close();
        return $stmt;
    }

    /**
     * 构建参数化SQL
     * @param $stmt PDOStatement
     * @param $values ContentValue
     */
    private function buildParams($stmt, $values)
    {
        if ($stmt && $values) {
            foreach ($values->getArray() as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
        }
    }

    /**
     * 插入数据
     * @param string $sql sql语句
     * @param ContentValue $values sql语句中的参数
     * @return bool 返回sql执行结果
     */
    private function insertData($sql, $values)
    {
        if ($values) {
            $stmt = $this->getStatement($sql, $values);
            return $stmt->execute();
        } else {
            return $this->mPdo->exec($sql) > 0;
        }
    }


    /**
     * 查询数据
     * @param $sql string sql语句
     * @param $values ContentValue sql语句中的参数
     * @return array 查询结果
     */
    private function queryData($sql, $values)
    {
        if ($values) {
            $stmt = $this->getStatement($sql, $values);
            $stmt->execute();
        } else {
            $stmt = $this->mPdo->query($sql);
        }
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }

    /**
     * 更新数据
     * @param string $sql
     * @param ContentValue $values
     * @param ContentValue $whereArgs
     * @return bool
     */
    private function updateData($sql, $values, $whereArgs)
    {
        if ($values) {
            $stmt = $this->getStatement($sql, $values);
            if ($whereArgs) {
                $this->buildParams($stmt, $whereArgs);
            }
            return $stmt->execute();
        } else {
            return $this->mPdo->exec($sql) > 0;
        }
    }

    /**
     * 删除数据
     * @param string $sql sql语句
     * @param ContentValue $whereArgs 删除条件的参数
     * @return bool 执行结果
     */
    private function deleteData($sql, $whereArgs)
    {
        if ($whereArgs) {
            $stmt = $this->getStatement($sql, $whereArgs);
            return $stmt->execute();
        } else {
            return $this->mPdo->exec($sql) > 0;
        }
    }

    /**
     * 插入数据
     * @param string $tableName 表名
     * @param ContentValue $contentValues 插入的值
     * @return bool
     */
    public function insert($tableName, $contentValues)
    {
        $sql = "INSERT INTO ";
        $sql .= $tableName;
        $sql .= '(';

        if ($contentValues && count($contentValues->keys())) {
            $sql .= $this->appendColumn($contentValues->keys());
            $sql .= ')';
            $sql .= " VALUES (";
            $sql .= $this->appendColumn($contentValues->keys(), true);
            $sql .= ')';
        } else {
            return false;
        }
//        var_dump($sql);
//        $this->insertData($sql, $contentValues);
        return $this->insertData($sql, $contentValues);
    }

    /**
     * 查询数据
     * @param $tableName string 表名
     * @param $columns array 查询的列名
     * @param $whereClause string 查询条件，格式：列名=:列名
     * @param $whereArgs ContentValue 查询条件参数，与参数$whereClause中的条目一一对应
     * @param null|string $groupBy 分组
     * @param null|string $having
     * @param null|string $orderBy 排序
     * @param null|string $limit
     * @return array 查询结果
     */
    public function query($tableName, $columns, $whereClause, $whereArgs,
                          $groupBy = null, $having = null, $orderBy = null, $limit = null)
    {
        $sql = "SELECT DISTINCT ";
        if ($columns || count($columns) == 0) {
            $sql .= '* ';
        } else {
            $sql .= $this->appendColumn($columns);
        }
        $sql .= "FROM " . $tableName;

        $sql .= $this->appendClause(" WHERE ", $whereClause);
        $sql .= $this->appendClause(" GROUP BY ", $groupBy);
        $sql .= $this->appendClause(" HAVING ", $having);
        $sql .= $this->appendClause(" ORDER BY ", $orderBy);
        $sql .= $this->appendClause(" LIMIT ", $limit);
        return $this->queryData($sql, $whereArgs);
    }

    private function appendClause($name, $clause)
    {
        if (!empty($clause)) {
            return $name . $clause;
        } else {
            return '';
        }
    }

    /**
     * 更新数据
     * @param $tableName string 表名
     * @param $values ContentValue 需要更新的值
     * @param $whereClause string 更新条件
     * @param $whereArgs ContentValue 更新参数
     * @return bool
     */
    public function update($tableName, $values, $whereClause = null, $whereArgs = null)
    {
        $sql = "UPDATE " . $tableName;
        $sql .= " SET ";

        $keys = $values->keys();
        for ($i = 0; $i < count($keys); $i++) {
            $sql .= ($i > 0) ? ',' : '';
            $sql .= $keys[$i] . "=:" . $keys[$i];
        }
        $sql .= $this->appendClause(" WHERE ", $whereClause);
        return $this->updateData($sql, $values, $whereArgs);
    }

    /**
     * 删除数据
     * @param string $tableName 表名
     * @param string $whereClause 删除条件
     * @param null|ContentValue $whereArgs 删除条件的参数
     * @return bool
     */
    public function delete($tableName, $whereClause, $whereArgs = null)
    {
        $sql = "DELETE FROM " . $tableName;
        $sql .= $this->appendClause(" WHERE ", $whereClause);
        return $this->deleteData($sql, $whereArgs);
    }

    /**
     * 执行sql语句
     * @param string $sql
     * @param ContentValue $selectionArgs
     */
    public function rawQuery($sql, $selectionArgs){

    }
}