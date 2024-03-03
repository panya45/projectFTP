<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <CENTER><font face = "Arial" size = "6" color = "blue" ><b> File Transfer Website</b></font></CENTER>

</head>
<body>
<link rel="stylesheet" href="style.css">
<form action="upload.php" method="post" enctype="multipart/form-data">
    <b><label for="file">Select File:</label></b>
    <input type="file" name="file" id="file" >
    <br>
    <br>
    <input type="submit" name="upload" value="Upload ไฟล์" style="background-color: #00ff00; color: #000000; font-size: 16px; font-weight: bold;">
</form>


<form action="download.php" method="post">

    <input type="submit" name="download" value="Download File" style="background-color: #1100ff; color: #ffffff; font-size: 16px; font-weight: bold;">
    
</form>

<form action="delete.php" method="post">
    <input type="submit" name="delete" value="Delete" style="background-color: #df1010; color: #ffffff; font-size: 16px; font-weight: bold;">
</form>

</body>
</html>

<?php
include('condb.php');

$FTP_SERVER = getenv('FTP_SERVER');
$FTP_USER = getenv('FTP_USER');
$FTP_PASS = getenv('FTP_PASS');

// เชื่อมต่อ FTP
$ftp_conn = ftp_connect($FTP_SERVER) 
or die("Could not connect to $FTP_SERVER");

if ($ftp_conn) {
    $_SESSION['ftp_conn'] = $ftp_conn;

    // เข้าสู่ระบบ FTP
    $login = ftp_login($ftp_conn, $FTP_USER, $FTP_PASS);

    if ($login) {
        $query = "SELECT * FROM files";
$result = mysqli_query($conn, $query);

if ($result) {
    // แสดงข้อมูลในตาราง
    echo "<table  border='1'bgcolor = 'white' >";
        echo "<tr>
                <th bgcolor = '#0ae172'> ชื่อไฟล์ </th>
                <th bgcolor = '#0ae172'> ขนาด</th>
                <th bgcolor = '#0ae172'> ประเภท</th>
                <th bgcolor = '#0ae172'> วันที่อัปโหลด</th>
            </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['size']}</td>
                <td>{$row['type']}</td>
                <td>{$row['date']}</td>
              </tr>";
    }

    echo "</table>";
}
    }
    // ปิดการเชื่อมต่อ FTP
    ftp_close($ftp_conn);
} else {
    echo "**Error: FTP connection failed**";
}
?>