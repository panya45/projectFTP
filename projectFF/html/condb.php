<?php
$db_host = 'db';  // ชื่อโฮสต์ของ MySQL
$db_user = 'root';       // ชื่อผู้ใช้ MySQL
$db_password = '1234';  // รหัสผ่าน MySQL
$db_name = 'data';      // ชื่อฐานข้อมูล

try{
    $conn = mysqli_connect($db_host,$db_user,$db_password,$db_name);
    mysqli_query($conn,"SET NAMES utf8");
}
catch(Exception $e){
    $error = $e->getMessage();
    error_log($error);
    echo $error;
}
?>