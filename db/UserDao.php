<?php

/**
 * 用户表相关操作，通过单例访问
 * User: XWdoor
 * Date: 2016/4/30
 * Time: 19:47
 */
class UserDao
{
    public static $Table_User = TABLE_USER;
    public static $Column_Id = "_id";
    public static $Column_User_Name = "userName";
    public static $Column_Real_Name = "realName";
    public static $Column_Phone = "phone";
    public static $Column_Password = "password";
    public static $Column_Mail = "mail";

    /** @var  UserDao */
    private static $sInstance;
    /** @var  SqliteHelper */
    private $mSqliteHelper;

    private function __construct()
    {
        require_once "SqliteHelper3.php";
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
     * 根据账号密码查找用户
     * @param $account string 账号：真实姓名、电话、邮箱
     * @param $password string 密码
     * @return User 查找到的用户
     */
    public function getUser($account, $password)
    {
        $value = new ContentValue();
        $value->put(self::$Column_Real_Name, $account);
        $value->put(self::$Column_Phone, $account);
        $value->put(self::$Column_Mail, $account);
        $value->put(self::$Column_Password, $password);

        $whereClause = sprintf("%s=:%s OR %s=:%s OR %s=:%s AND %s=:%s",
            self::$Column_Real_Name, self::$Column_Real_Name,
            self::$Column_Phone, self::$Column_Phone,
            self::$Column_Mail, self::$Column_Mail,
            self::$Column_Password, self::$Column_Password);

        $result = $this->mSqliteHelper->query(self::$Table_User, null, $whereClause, $value);
        if ($result) {
            $row = $result[0];

            $user = new User();
            $user->id = $row[self::$Column_Id];
            $user->userName = $row[self::$Column_User_Name];
            $user->realName = $row[self::$Column_Real_Name];
            $user->phone = $row[self::$Column_Phone];
            $user->mail = $row[self::$Column_Mail];
            return $user;
        } else {
            return null;
        }
    }

    /**
     * 添加用户
     * @param User $user 用户信息
     * @return bool 添加结果
     */
    public function addUser($user)
    {
        $values = new ContentValue();
        $values->put(self::$Column_User_Name,$user->userName);
        $values->put(self::$Column_Real_Name,$user->realName);
        $values->put(self::$Column_Phone,$user->phone);
        $values->put(self::$Column_Mail,$user->mail);
        $values->put(self::$Column_Password,$user->password);

        return $this->mSqliteHelper->insert(TABLE_USER,$values);
    }
}