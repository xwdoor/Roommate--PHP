<?php

/**
 * 服务器响应类
 * User: XWdoor
 * Date: 2016/4/27
 * Time: 19:26
 */
class ResponseJson
{
    /** @var int $code 返回代码，若code为0，则属性error无效 */
    public $code;
    /** @var  string $error 错误消息 */
    public $error;
    /** @var string $result 结果,Json格式 */
    public $result;


    public function __construct()
    {
        $this->code = 0;
        $this->error = '';
        $this->result = '';
    }


}