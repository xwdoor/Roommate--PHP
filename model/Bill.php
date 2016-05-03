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

    /**
     * Bill constructor.
     * @param float $money
     * @param int $payerId
     * @param int $recordId
     * @param int $billType
     * @param string $date
     * @param string $desc
     */
    public function __construct($money = null, $payerId = null, $recordId = null,
                                $billType = null, $date = null, $desc = null)
    {
        $this->money = $money;
        $this->payerId = $payerId;
        $this->recordId = $recordId;
        $this->billType = $billType;
        $this->date = $date;
        $this->desc = $desc;
        $this->isFinish = 0;
    }


}