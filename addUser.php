<?php
/**
 * Created by PhpStorm.
 * User: XWdoor
 * Date: 2016/5/1
 * Time: 15:00
 */
var_dump("开始打开数据库");
//$db = new SQLiteDatabase('filename');
//$sqliteHelper = new SqliteHelper();
//sqlite_open('database2');
$sqlite = new SQLite3('database3');
$sqlite->open("databse3");

var_dump("打开数据库成功");