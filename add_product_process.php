<?php
session_start();
include 'db.php'; // การเชื่อมต่อฐานข้อมูลด้วย PDO

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $productStock = $_POST['product_stock'];
    $image = $_FILES['product_image'];

    // ตรวจสอบไฟล์รูปภาพ
    if ($image['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/products/";
        $fileName = basename($image['name']);
        $targetFilePath = $targetDir . $fileName;

        // ตรวจสอบว่าโฟลเดอร์อัปโหลดมีอยู่หรือไม่
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // ย้ายไฟล์ไปยังโฟลเดอร์เป้าหมาย
        if (move_uploaded_file($image['tmp_name'], $targetFilePath)) {
            try {
                // เตรียมคำสั่ง SQL
                $stmt = $conn->prepare("INSERT INTO products (product_name, product_price, product_stock, image, created_at) 
                                       VALUES (:product_name, :product_price, :product_stock, :image, NOW())");
                
                // ผูกค่าตัวแปร
                $stmt->bindParam(':product_name', $productName);
                $stmt->bindParam(':product_price', $productPrice);
                $stmt->bindParam(':product_stock', $productStock);
                $stmt->bindParam(':image', $fileName);

                // ดำเนินการคำสั่ง
                $stmt->execute();

                $_SESSION['success'] = "Product added successfully!";
                header("Location: cart.php");
                exit();
            } catch (PDOException $e) {
                $_SESSION['error'] = "Database error: " . $e->getMessage();
                header("Location: add_product.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Failed to upload image.";
            header("Location: add_product.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Please select a valid image file.";
        header("Location: add_product.php");
        exit();
    }
} else {
    header("Location: add_product.php");
    exit();
}
?>
