<?php
/**
 * 账单信息类
 * User: XWdoor
 * Date: 2016/4/27
 * Time: 20:28
 */
//namespace Roommate\model;


class Bill
{
    public $id;
    
    /** @var float 金额 */
    public $money;

    /** @var int 付款人 */
    public $payerId;

    /** @var int 记录者 */
    public $recordId;

    /** @var int 账单类型 */
    public $billType;

    /** @var bool 是否已结算 */
    public $isFinish;

    /** @var string 日期 */
    public $date;

    /** @var string 描述 */
    public $desc;
}