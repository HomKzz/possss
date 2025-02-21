<?php
include 'sidebar.php';
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT sales.sales_id, customer.cust_name, sales.sale_date  , sales.total
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


$activePage = 'sales';

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Product</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

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
                    <h1 class="h3 mb-2 text-gray-800 mt-4">รายการขาย</h1>


                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">รายการทั้งหมด</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>รายการ</th>
                                            <th>ลูกค้า</th>
                                            <th>วันที่ขาย</th>
                                            <th>ราคารวม</th>
                                            <th>ประวัติ</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>รายการ</th>
                                            <th>ลูกค้า</th>
                                            <th>วันที่ขาย</th>
                                            <th>ราคารวม</th>
                                            <th>ประวัติ</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <!-- End of Main Content -->


            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">รายละเอียด</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>รหัสการขาย : <span id="sales-id"></span></p>
                            <p>ข้อมูล : <span id="details"></span></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

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

    <script>


        // รับข้อมูล JSON ที่ส่งมาจาก PHP
        const dataArray = <?php echo $jsonData; ?>;


        // เพิ่มข้อมูลในตาราง HTML
        const tableBody = document.querySelector("#dataTable tbody");
        dataArray.forEach(data => {
            const row = document.createElement('tr');
            row.innerHTML = `
        <td>${data.sales_id}</td>
        <td>${data.cust_name}</td>
        <td>${data.sale_date}</td>
        <td>${data.total}</td>
        <td>
            <button type="button" class="btn btn-primary" 
                    data-toggle="modal" 
                    data-target="#exampleModal" 
                    data-id="${data.sales_id}">
                รายละเอียด
            </button>
        </td>
    `;
            tableBody.appendChild(row);
        });

    </script>

    <script>
        $(document).ready(function () {
            $('#exampleModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var salesId = button.data('id');

                if (!salesId) {
                    console.error("Sales ID is missing");
                    return;
                }


                // ใช้ jQuery AJAX เพื่อส่ง JSON ไปยัง PHP
                const data = {
                    sales_id: salesId
                };

                $.ajax({
                    url: 'sales_detail.php', // URL ของ PHP ที่จะประมวลผล
                    type: 'POST',       // ใช้คำขอแบบ POST
                    data: JSON.stringify(data), // แปลงข้อมูลเป็น JSON String
                    contentType: 'application/json; charset=utf-8', // กำหนด Content-Type
                    dataType: 'json',   // คาดหวังการตอบกลับเป็น JSON
                    success: function (response) {
                        // ตรวจสอบว่า response มีสถานะ success
                        if (response.status === 'success') {
                            console.log(response);
                            var modal = $('#exampleModal');

                            // อัปเดตรายละเอียดใน modal
                            modal.find('#sales-id').text(response.data.sales_id);

                            // สร้าง HTML สำหรับรายละเอียดสินค้า
                            var detailsHTML = `
        <ul>
            <li>ชื่อลูกค้า: ${response.data.cust_name}</li>
            <li>วันที่ขาย: ${response.data.sale_date}</li>
            <li>ราคารวม: ${response.data.total} บาท</li>
            <li>สินค้า:</li>
            <ul>
    `;

                            // วนลูปสร้างรายการสินค้า
                            response.data.products.forEach(product => {
                                detailsHTML += `
                                
            <li><img src="uploads/products/${product.image}" alt="${product.product_name}" 
                     style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">${product.product_name} (${product.product_price} บาท) จำนวน: ${product.quantity}</li>
        `;
                            });

                            // ปิด tag ของ HTML
                            detailsHTML += `
            </ul>
        </ul>
    `;

                            // แสดงรายละเอียดใน modal
                            modal.find('#details').html(detailsHTML);
                        } else {
                            console.error('Error in response:', response.message);
                            $('#details').text('ไม่พบข้อมูล');
                        }

                    },
                    error: function (xhr, status, error) {
                        // จัดการข้อผิดพลาด
                        console.error('AJAX Error:', status, error);
                    }
                });


            });
        });

    </script>



</body>

</html>