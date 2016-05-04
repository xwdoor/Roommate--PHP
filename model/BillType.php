<?php

/**
 * Created by PhpStorm.
 * User: XiaoWei
 * Date: 2016/5/4 004
 * Time: 10:17
 */
class BillType
{
    /** @var  int */
    public $typeId;

    /** @var  string */
    public $typeName;

    /**
     * BillType constructor.
     * @param int $typeId
     * @param string $typeName
     */
    public function __construct($typeId = null, $typeName = null)
    {
        $this->typeId = $typeId;
        $this->typeName = $typeName;
    }


}