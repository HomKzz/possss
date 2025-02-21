<?php
include 'sidebar.php';
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT sales.sales_id, customer.cust_name, sales.sale_date, sales.total
        FROM sales 
        INNER JOIN customer ON sales.cust_id = customer.cust_id";
$result = $conn->prepare($sql);
$result->execute();

// เก็บผลลัพธ์ใน array
$dataArray = [];
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $dataArray[] = $row;
}
$jsonData = json_encode($dataArray);

$sqlitems = "SELECT * FROM products";
$resultitems = $conn->prepare($sqlitems);
$resultitems->execute();

// เก็บผลลัพธ์ใน array
$dataArrayitems = [];
while ($row = $resultitems->fetch(PDO::FETCH_ASSOC)) {
    $dataArrayitems[] = $row;
}
$jsonDataitems = json_encode($dataArrayitems);

$activePage = 'sales';
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Product</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <?php renderSidebar($activePage); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800 mt-4">จัดการสินค้า</h1>

                    <!-- Modal -->
                    <div class="d-flex justify-content-end mr-5 mb-4">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProductModal">
                            เพิ่มสินค้า
                        </button>
                    </div>

                    <!-- Table -->
                    <table class="table table-striped text-center">
                        <thead>
                            <tr>
                                <th></th>
                                <th>รหัสสินค้า</th>
                                <th>ชื่อสินค้า</th>
                                <th>ราคา</th>
                                <th>จำนวนคงเหลือ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // แสดงข้อมูลจาก $dataArrayitems
                            foreach ($dataArrayitems as $item) {
                                echo "<tr>";
                                echo "<td><img src='uploads/products/" . $item['image'] . "' width='100' height='100'></td>";
                                echo "<td>" . $item['product_id'] . "</td>";
                                echo "<td>" . $item['product_name'] . "</td>";
                                echo "<td>" . number_format($item['product_price'], 2) . "</td>";
                                echo "<td>" . $item['product_stock'] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                    <!-- Add Product Modal -->
                    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addProductModalLabel">เพิ่มสินค้าใหม่</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="add_product_process.php" method="POST" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label for="product_name" class="form-label">ชื่อสินค้า</label>
                                            <input type="text" name="product_name" id="product_name" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="product_price" class="form-label">ราคา</label>
                                            <input type="number" name="product_price" id="product_price" class="form-control" step="0.01" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="product_stock" class="form-label">จำนวนคงเหลือ</label>
                                            <input type="number" name="product_stock" id="product_stock" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="product_image" class="form-label">รูปภาพสินค้า</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                                                </div>
                                                <div class="custom-file">
                                                    <input type="file" name="product_image" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" accept="image/*" required>
                                                    <label class="custom-file-label" for="inputGroupFile01">เลือกไฟล์</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                                            <button type="submit" class="btn btn-primary">บันทึกสินค้า</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>
