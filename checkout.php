<?php
session_start();
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payload'])) {
    // รับข้อมูล JSON จากฟอร์ม
    $payload = json_decode($_POST['payload'], true);

    // ตรวจสอบว่ามีข้อมูล customerId และ cart
    $customerId = $payload['customerId'] ?? null;
    $cart = $payload['cart'] ?? [];

    // ตรวจสอบว่าตะกร้าสินค้ามีสินค้า
    if (empty($cart)) {
        echo "ตะกร้าสินค้าว่าง";
        exit;
    }

    // แสดงข้อมูล Customer ID
    echo "<h2>ข้อมูลลูกค้า</h2>";
    echo "<p>รหัสลูกค้า: $customerId</p>";

    $sql = "insert into sales (cust_id) values (:cust_id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':cust_id', $customerId);
    $stmt->execute();

    $salesId = $conn->lastInsertId();

    // แสดงรายการสินค้าในตะกร้า
    echo "<h2>รายการสินค้าในตะกร้า</h2>";
    echo "<ul>";
    try {
        foreach ($cart as $item) {
            $sqlsales_items = "insert into sale_items (sales_id, product_id, quantity) values (:sales_id, :product_id, :quantity)";
            $stmt = $conn->prepare($sqlsales_items);
            $stmt->bindParam(':sales_id', $salesId);
            $stmt->bindParam(':product_id', $item['id']);
            $stmt->bindParam(':quantity', $item['quantity']);
            $stmt->execute();
            $_SESSION['success'] = "Product added successfully!";
        }

        $sql = "UPDATE products SET product_stock = product_stock - :quantity WHERE product_id = :product_id";
        $stmt = $conn->prepare($sql);
        foreach ($cart as $item) {
            $stmt->bindParam(':quantity', $item['quantity']);
            $stmt->bindParam(':product_id', $item['id']);
            $stmt->execute();
        }

        header("Location: cart.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    echo "<li>ชื่อสินค้า: " . $item['name'] . ", จํานวน: " . $item['quantity'] . "</li>";

    echo "</ul>";
} else {
    echo "ไม่มีข้อมูลที่ส่งมา";
}
?>