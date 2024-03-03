<?php
session_start();

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

        // ตรวจสอบว่ามีการร้องขอ download ไฟล์หรือไม่
        if (isset($_GET["file"])) {
        $filename = $_GET["file"];
        $remote_file = $filename;
        $local_file = tempnam(sys_get_temp_dir(), 'download_');

                // ดาวน์โหลดไฟล์
                if (ftp_get($ftp_conn, $local_file, $remote_file, FTP_BINARY)) {
                    // กำหนด Content-Type ตามประเภทของไฟล์
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime_type = finfo_file($finfo, $local_file);
                    finfo_close($finfo);

                    header('Content-Type: ' . $mime_type);
                    header('Content-Disposition: attachment; filename="' . $filename . '"');
                    readfile($local_file);

                    // ปิดการเชื่อมต่อ FTP
                    ftp_close($ftp_conn);
                    exit(); // ออกจากการทำงานหลังจากที่ได้ส่งไฟล์แล้ว
                } else {
                    header("HTTP/1.1 500 Internal Server Error");
                    exit();                    
        }
        }

        // แสดงลิงก์ไปยังไฟล์ทั้งหมด
        echo "<h2>File List for download</h2>";
        echo "<font face = 'Arial'size = '6' ";
        echo "<ul>";
        foreach ($file_list as $file) {
            echo "<li><a href=\"download.php?file=$file\"> $file</a></li>";
        }
            echo "</ul>";
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
    <b><font face = "Arial" size = "6" color = "blue" ></font>
</body>
</html>