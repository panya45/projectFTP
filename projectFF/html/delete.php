<?php

include('condb.php');

$FTP_SERVER = getenv('FTP_SERVER');
$FTP_USER = getenv('FTP_USER');
$FTP_PASS = getenv('FTP_PASS');

// เชื่อมต่อ FTP
$ftp_conn = ftp_connect($FTP_SERVER) or die("Could not connect to $FTP_SERVER");

if ($ftp_conn) {
  $_SESSION['ftp_conn'] = $ftp_conn;

  // เข้าสู่ระบบ FTP
  $login = ftp_login($ftp_conn, $FTP_USER, $FTP_PASS);

  if ($login) {
    // FTP login สำเร็จ
    $file_list = ftp_nlist($ftp_conn, ".");

    // ตรวจสอบว่ามีการร้องขอ delete ไฟล์หรือไม่
    if (isset($_POST["files"])) {
      $filenames = $_POST["files"];

      foreach ($fsilename as $filename) {
        // ลบไฟล์
        if (ftp_delete($ftp_conn, $filename)) {

          // ทำการ query เพื่อลบ record จากฐานข้อมูล
          $deleteQuery = "DELETE FROM files WHERE name = '$filename'";
          $result = mysqli_query($conn, $deleteQuery);

          if ($result) {
            // ทำการ redirect กลับไปยังหน้า index.php และทำการรีเฟรชหน้า
            header("Location: index.php");
          } 
        } 
      }
    }

    // แสดงรายการไฟล์พร้อม checkbox สำหรับเลือก
    echo "<h2>File List</h2>";
    echo "<form action='delete.php' method='post'";
    echo "<ul>";
    echo "<font face = 'Arial'size = '5' ";
    foreach ($file_list as $file) {
      echo "<br><input type='checkbox' name='files[]' value='$file'> $file</br>";
    }
    echo "</ul>";
    
    // ปุ่ม submit สำหรับลบไฟล์ที่เลือก
    echo "<br>";
    echo "<input type='submit' name='submit' value='Delete' style = 'background-color: #df1010; color: #ffffff; font-size: 16px; font-weight: bold';>";
    echo "</form>";

  } else {
    echo "FTP Login Failed";
  }
} else {
  echo "Error: FTP connection failed";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  
</body>
</html>
