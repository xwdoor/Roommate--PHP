<?php
/**
 * Created by PhpStorm.
 * User: XiaoWei
 * Date: 2016/5/3 003
 * Time: 14:48
 */

require_once "../model/User.php";

$user = new User();
$user->realName="肖威";

echo $user->realName;