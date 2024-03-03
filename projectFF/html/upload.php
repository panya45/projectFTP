<?php
session_start();

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

        // ตรวจสอบว่ามีการอัปโหลดไฟล์หรือไม่
        if (isset($_FILES["file"])) {
            $filename = $_FILES["file"]["name"];
            $tmp_name = $_FILES["file"]["tmp_name"];
            $fileSize = $_FILES["file"]["size"];
            $fileType = $_FILES["file"]["type"];
            $fileDate = date("Y-m-d H:i:s");

            // อัปโหลดไฟล์ไปยังเซิร์ฟเวอร์ FTP
            $upload = ftp_put($_SESSION['ftp_conn'], $filename, $tmp_name, FTP_BINARY);
            
            if ($upload) {
                // อัปโหลดไฟล์สำเร็จ

                // เพิ่มข้อมูลไฟล์ลงในฐานข้อมูล
                $insertQuery = "INSERT INTO files (name, size, type, date) VALUES ('$filename', $fileSize, '$fileType','$fileDate')";
                $result = mysqli_query($conn, $insertQuery);

                if ($result) {              
                    // ทำการ redirect กลับไปยังหน้า index.php และทำการรีเฟรชหน้า
                    header("Location: index.php");
                    exit();
                } 
                
            } else {
                echo "ไม่สามารถอัปโหลดไฟล์ไปยังเซิร์ฟเวอร์ FTP ได้";
            }
        }
        
        // ปิดการเชื่อมต่อ FTP
        ftp_close($ftp_conn);
    } else {
        echo "FTP Login Failed";
    }
} else {
    echo "Error: FTP connection failed";
}
?>
