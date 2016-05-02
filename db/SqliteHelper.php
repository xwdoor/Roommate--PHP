<?php

/**
 * Sqlite数据库操作
 * User: XWdoor
 * Date: 2016/4/27
 * Time: 19:44
 */
define('DATABASE_NAME', 'Roommate.sqlite');
define("TABLE_BILL", "R_BillData");
define("TABLE_USER", "R_User");

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
            "billType integer," .
            "date varchar(20)," .
            "desc text)";
        //如果不存在，则创建
        if (!in_array(TABLE_BILL, $this->tables) && !$this->isTableExist(TABLE_BILL)) {
            $this->mPdo->exec($createTableBill);
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
            var_dump("create table user");
            $this->mPdo->exec($createUser);
            $sql = "INSERT INTO R_User (userName, realName, phone, password, mail) VALUES ('xwdoor', '肖威', '18684033888', 'xwdoor', 'xwdoor@126.com')";
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
     * 插入数据
     * @param string $sql sql语句
     * @param ContentValue $values sql语句中的参数
     * @return bool 返回sql执行结果
     */
    private function insertData($sql, $values=null)
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
    private function queryData($sql, $values=null)
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
     * 构建PDOStatement
     * @param string $sql sql语句
     * @param ContentValue $values sql语句中的参数
     * @return PDOStatement
     */
    private function getStatement($sql, $values)
    {
        $stmt = $this->mPdo->prepare($sql);
        foreach ($values->getArray() as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        //不能close，不然$result不能读取
//            $stmt->close();
        return $stmt;
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
        $size = ($contentValues && count($contentValues->keys()) > 0) ?
            0 : count($contentValues->keys());
        if ($size > 0) {
            $sql .= $this->appendColumn($contentValues->keys());
            $sql .= ')';
            $sql .= " VALUES (";
            $sql .= $this->appendColumn($contentValues->keys(), true);
            $sql .= ')';
        } else {
            return false;
        }

//        $this->insertData($sql, $contentValues);
        return $this->insertData($sql, $contentValues);
    }

    /**
     * 查询数据
     * @param $tableName string 表名
     * @param $columns array 查询的列名
     * @param $whereClause string 查询条件，格式：列名=:列名
     * @param $whereArgs ContentValue 查询条件参数，与参数$whereClause中的条目一一对应
     * @return array 查询结果
     */
    public function query($tableName, $columns, $whereClause, $whereArgs)
    {
        $sql = "SELECT DISTINCT ";
        if ($columns || count($columns) == 0) {
            $sql .= '* ';
        } else {
            $sql .= $this->appendColumn($columns);
        }
        $sql .= "FROM " . $tableName;
        if (!empty($whereClause)) {
            $sql .= " WHERE " . $whereClause;
        }
        return $this->queryData($sql, $whereArgs);
    }

    public function update($tableName)
    {

    }

}