<?php
// ตั้งค่าข้อมูลการเชื่อมต่อฐานข้อมูล
$servername = "localhost"; // ชื่อเซิร์ฟเวอร์
$username = "root"; // ชื่อผู้ใช้ฐานข้อมูล
$password = ""; // รหัสผ่านของผู้ใช้ฐานข้อมูล
$dbname = "pos_system"; // ชื่อฐานข้อมูลที่คุณใช้

try {
    // เชื่อมต่อฐานข้อมูลโดยใช้ PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    // ตั้งค่าให้ PDO แสดงข้อผิดพลาดเมื่อเกิดปัญหาการเชื่อมต่อ
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ถ้าเชื่อมต่อสำเร็จ จะไม่มีข้อความแสดง
    // echo "Connected successfully"; 

} catch(PDOException $e) {
    // หากเกิดข้อผิดพลาดในการเชื่อมต่อ จะถูกจับในนี้
    echo "Connection failed: " . $e->getMessage();
}
?>
