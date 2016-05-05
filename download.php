<?php
/**
 * Created by PhpStorm.
 * User: XiaoWei
 * Date: 2016/5/5 005
 * Time: 15:52
 */

$file = "/roommate-release.apk";

$filename = basename($file);

header("Content-type: application/octet-stream");

//处理中文文件名
$ua = $_SERVER["HTTP_USER_AGENT"];
$encoded_filename = rawurlencode($filename);
if (preg_match("/MSIE/", $ua)) {
    header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
} else if (preg_match("/Firefox/", $ua)) {
    header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
} else {
    header('Content-Disposition: attachment; filename="' . $filename . '"');
}

//让Xsendfile发送文件
header("X-Sendfile: $file");