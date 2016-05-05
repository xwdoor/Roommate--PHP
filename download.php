<?php
/**
 * Created by PhpStorm.
 * User: XiaoWei
 * Date: 2016/5/5 005
 * Time: 15:52
 */

//http://xwdoor.net/roommate/download.php

$file = 'roommate-release.apk';
$name = time() . '.zip';
$obj = new FileDownload();
$flag = $obj->download($file);
//$flag = $obj->download($file, $name, true); // 断点续传
if (!$flag) {
    echo 'file not exists';
}

/** php下载类,支持断点续传
 *  download: 下载文件
 *  setSpeed: 设置下载速度
 *  getRange: 获取header中Range
 */
class FileDownload
{ // class start

    private $_speed = 512;  // 下载速度

    /** 下载
     * @param String $file 要下载的文件路径
     * @param String $name 文件名称,为空则与下载的文件名称一样
     * @param boolean $reload 是否开启断点续传
     * @return string
     */
    public function download($file, $name = '', $reload = false)
    {
        if (file_exists($file)) {
            if ($name == '') {
                $name = basename($file);
            }

            $fp = fopen($file, 'rb');
            $file_size = filesize($file);
            $ranges = $this->getRange($file_size);

            header('cache-control:public');
            header('content-type:application/octet-stream');
            header('content-disposition:attachment; filename=' . $name);

            if ($reload && $ranges != null) { // 使用续传
                header('HTTP/1.1 206 Partial Content');
                header('Accept-Ranges:bytes');

                // 剩余长度
                header(sprintf('content-length:%u', $ranges['end'] - $ranges['start']));

                // range信息
                header(sprintf('content-range:bytes %s-%s/%s', $ranges['start'], $ranges['end'], $file_size));

                // fp指针跳到断点位置
                fseek($fp, sprintf('%u', $ranges['start']));
            } else {
                header('HTTP/1.1 200 OK');
                header('content-length:' . $file_size);
            }

            while (!feof($fp)) {
                echo fread($fp, round($this->_speed * 1024, 0));
                ob_flush();
                //sleep(1); // 用于测试,减慢下载速度
            }

            ($fp != null) && fclose($fp);

        } else {
            return '';
        }
    }

    /** 设置下载速度
     * @param int $speed
     */
    public function setSpeed($speed)
    {
        if (is_numeric($speed) && $speed > 16 && $speed < 4096) {
            $this->_speed = $speed;
        }
    }

    /** 获取header range信息
     * @param int $file_size 文件大小
     * @return Array
     */
    private function getRange($file_size)
    {
        if (isset($_SERVER['HTTP_RANGE']) && !empty($_SERVER['HTTP_RANGE'])) {
            $range = $_SERVER['HTTP_RANGE'];
            $range = preg_replace('/[\s|,].*/', '', $range);
            $range = explode('-', substr($range, 6));
            if (count($range) < 2) {
                $range[1] = $file_size;
            }
            $range = array_combine(array('start', 'end'), $range);
            if (empty($range['start'])) {
                $range['start'] = 0;
            }
            if (empty($range['end'])) {
                $range['end'] = $file_size;
            }
            return $range;
        }
        return null;
    }
}