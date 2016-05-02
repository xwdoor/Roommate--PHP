<?php

/**
 * Json解析
 * User: XWdoor
 * Date: 2016/5/1
 * Time: 21:28
 */
class JsonUtils
{
    /**
     * 将对象转换为Json字符串
     * @param mixed $value
     * @return string json字符串
     */
    public static function toJson($value){
        $str = json_encode($value);
        return self::decodeUnicode($str);
    }

    /**
     * 将Json字符串解析为对象
     * @param string $json json字符串
     * @param bool $assoc 是否转换成数组
     * @return mixed
     */
    public static function fromJson($json,$assoc = false){
        return json_decode($json,$assoc);
    }

    /**
     * 解析json字符串中的中文
     * @param $str
     * @return mixed
     */
    private static function decodeUnicode($str)
    {
        return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
            create_function(
                '$matches',
                'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
            ),
            $str);

    }
}