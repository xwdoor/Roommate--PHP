<?php

/**
 * 用户信息类
 * User: XWdoor
 * Date: 2016/4/27
 * Time: 19:30
 */
//namespace Roommate\model;

class User
{

    /**
     * @var int
     * 用户Id
     */
    public $id;

    /**
     * @var string
     * 用户名
     */
    public $userName;

    /**
     * @var string
     * 真实姓名
     */
    public $realName;

    /**
     * @var string
     * 电话
     */
    public $phone;

    /**
     * @var string
     * 密码
     */
    public $password;

    /**
     * @var string
     * 邮箱
     */
    public $mail;

    /**
     * User constructor.
     * @param string $realName
     * @param string $phone
     * @param string $mail
     * @param string $password
     */
    public function __construct($realName = null, $phone = null, $mail = null, $password = null)
    {
        $this->realName = $realName;
        $this->phone = $phone;
        $this->mail = $mail;
        $this->password = $password;
    }
}