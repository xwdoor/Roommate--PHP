<?php

/**
 * 用户表相关操作，通过单例访问
 * User: XWdoor
 * Date: 2016/4/30
 * Time: 19:47
 */
class UserDao
{
    public static $TABLE_USER = TABLE_USER;
    public static $COLUMN_ID = "_id";
    public static $COLUMN_USER_NAME = "userName";
    public static $COLUMN_REAL_NAME = "realName";
    public static $COLUMN_PHONE = "phone";
    public static $COLUMN_PASSWORD = "password";
    public static $COLUMN_MAIL = "mail";

    /** @var  UserDao */
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
     * 根据账号密码查找用户
     * @param $account string 账号：真实姓名、电话、邮箱
     * @param $password string 密码
     * @return User 查找到的用户
     */
    public function getUser($account, $password)
    {
        $value = new ContentValue();
        $value->put(self::$COLUMN_REAL_NAME, $account);
        $value->put(self::$COLUMN_PHONE, $account);
        $value->put(self::$COLUMN_MAIL, $account);
        $value->put(self::$COLUMN_PASSWORD, $password);

        $whereClause = sprintf("%s=:%s OR %s=:%s OR %s=:%s AND %s=:%s",
            self::$COLUMN_REAL_NAME, self::$COLUMN_REAL_NAME,
            self::$COLUMN_PHONE, self::$COLUMN_PHONE,
            self::$COLUMN_MAIL, self::$COLUMN_MAIL,
            self::$COLUMN_PASSWORD, self::$COLUMN_PASSWORD);

        $result = $this->mSqliteHelper->query(self::$TABLE_USER, null, $whereClause, $value);
        if ($result) {
            $row = $result[0];

            $user = new User();
            $user->id = $row[self::$COLUMN_ID];
            $user->userName = $row[self::$COLUMN_USER_NAME];
            $user->realName = $row[self::$COLUMN_REAL_NAME];
            $user->phone = $row[self::$COLUMN_PHONE];
            $user->mail = $row[self::$COLUMN_MAIL];
            return $user;
        } else {
            return null;
        }
    }

    /**
     * 获取用户列表
     * @return array|null
     */
    public function getAllUser()
    {
        $result = $this->mSqliteHelper->query(self::$TABLE_USER, null, null, null);
        if ($result) {
            $users = array();
            for ($i = 0; $i < count($result); $i++) {
                $row = $result[$i];

                $user = new User();
                $user->id = $row[self::$COLUMN_ID];
                $user->userName = $row[self::$COLUMN_USER_NAME];
                $user->realName = $row[self::$COLUMN_REAL_NAME];
                $user->phone = $row[self::$COLUMN_PHONE];
                $user->mail = $row[self::$COLUMN_MAIL];

                $users[$i] = $user;
            }
            return $users;

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
        $values->put(self::$COLUMN_USER_NAME, $user->userName);
        $values->put(self::$COLUMN_REAL_NAME, $user->realName);
        $values->put(self::$COLUMN_PHONE, $user->phone);
        $values->put(self::$COLUMN_MAIL, $user->mail);
        $values->put(self::$COLUMN_PASSWORD, $user->password);

        return $this->mSqliteHelper->insert(self::$TABLE_USER, $values);
    }

    public function updateUser($user)
    {

    }
}