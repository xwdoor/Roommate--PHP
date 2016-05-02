<?php

/**
 * Created by PhpStorm.
 * User: XWdoor
 * Date: 2016/4/28
 * Time: 17:53
 */
class ContentValue
{
    /** @var array */
    private $mValues;

    public function __construct()
    {
        $this->mValues = array();
    }

    /**
     * 添加键值对
     * @param string $key 键
     * @param mixed $value 值
     */
    public function put($key, $value)
    {
        $this->mValues[$key] = $value;
    }

    /**
     * 根据键获取值
     * @param string $key 键
     * @return mixed
     */
    public function get($key)
    {
        return $this->mValues[$key];
    }


    /**
     * 获取所有的键
     * @return array
     */
    public function keys()
    {
        return array_keys($this->mValues);
    }

    /**
     * @return array
     */
    public function getArray()
    {
        return $this->mValues;
    }

}