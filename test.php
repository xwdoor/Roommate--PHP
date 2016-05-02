<?php
/**
 * Created by PhpStorm.
 * User: XWdoor
 * Date: 2016/4/28
 * Time: 21:59
 */

function query()
{
    require_once "db/SqliteHelper3.php";
    require_once "db/ContentValue.php";
    $sqliteHelper = new SqliteHelper3();
    if (!empty($sqliteHelper)) {
        $result = $sqliteHelper->query(TABLE_USER,null,null,null);
        var_dump($result->fetchArray());
        
    }
}

function insert()
{
    echo "<h1>".'hello'."</h1>";
}

?>

<html>
<head>
</head>


<body>
<input type="button" name="show" id="show" value="查询数据" onClick="queryData();"/>
<input type="button" name="show2" id="show2" value="插入数据" onClick="insertData();"/>
<div id="abc"></div>


<script type="text/javascript" language="JavaScript">
    function queryData() {
        alert('hello');
    }
    function insertData() {
        var abcd =document.getElementById('abc');
        abcd.innerHTML='<?php query(); ?>';

    }
</script>
</body>
</html>