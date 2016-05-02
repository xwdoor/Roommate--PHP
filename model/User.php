<?php

/**
 * Created by PhpStorm.
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
     * 真是姓名
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
}