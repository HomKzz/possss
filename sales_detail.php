<?php
include 'db.php';

// ตั้งค่า Header เพื่อระบุว่าเป็น JSON
header('Content-Type: application/json');

// รับ JSON String จาก AJAX
$jsonData = file_get_contents("php://input");
$data = json_decode($jsonData, true);

if ($data && isset($data['sales_id']) && is_numeric($data['sales_id'])) {
    $salesId = $data['sales_id'];

    try {
        // Query ดึงข้อมูล
        $sql = "SELECT 
                    sales.sales_id, 
                    customer.cust_name, 
                    sales.sale_date, 
                    sales.total,
                    products.product_name, 
                    products.product_price, 
                    products.product_id, 
                    products.image,
                    st.quantity
                FROM sales 
                INNER JOIN customer ON sales.cust_id = customer.cust_id
                LEFT JOIN sale_items st ON sales.sales_id = st.sales_id 
                LEFT JOIN products ON st.product_id = products.product_id
                WHERE sales.sales_id = :sales_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':sales_id', $salesId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // ดึงข้อมูลทั้งหมด
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($results) {
                // จัดโครงสร้างข้อมูล
                $response = [
                    'status' => 'success',
                    'data' => [
                        'sales_id' => $results[0]['sales_id'],
                        'cust_name' => $results[0]['cust_name'],
                        'sale_date' => $results[0]['sale_date'],
                        'total' => $results[0]['total'],
                        'products' => [] // สำหรับเก็บสินค้า
                    ]
                ];

                // เพิ่มสินค้าใน array products
                foreach ($results as $row) {
                    if (!empty($row['product_id'])) { // ตรวจสอบว่ามีข้อมูลสินค้า
                        $response['data']['products'][] = [
                            'product_id' => $row['product_id'],
                            'product_name' => $row['product_name'],
                            'product_price' => $row['product_price'],
                            'quantity' => $row['quantity'],
                            'image' => $row['image']
                        ];
                    }
                }
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Sales not found or no products available'
                ];
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Failed to execute query'
            ];
        }
    } catch (PDOException $e) {
        $response = [
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ];
    }
} else {
    $response = [
        'status' => 'error',
        'message' => 'Invalid sales ID or data missing'
    ];
}

// ส่งข้อมูลกลับในรูปแบบ JSON
echo json_encode($response);
?>
