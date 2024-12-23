<?php
include 'sidebar.php';
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$activePage = 'cart';


$sql = "SELECT * FROM products";
$stmt = $conn->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as $row) {
    $productName = $row['product_name'];
    $productPrice = $row['product_price'];
    $productStock = $row['product_stock'];
    $image = $row['image'];
}

if (isset($_POST['search'])) {
    $searchQuery = $_POST['search'];
    $filteredResults = array_filter($results, function ($row) use ($searchQuery) {
        return strpos(strtolower($row['product_name']), strtolower($searchQuery)) !== false;
    });
    $results = $filteredResults;
}


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

    <style>
        .custom-scrollbar {
            overflow-y: auto;
            /* ให้สามารถเลื่อนแนวตั้งได้ */
            scrollbar-width: thin;
            /* ปรับขนาด scrollbar ให้บาง */
            scrollbar-color: transparent transparent;
            /* ซ่อนสี scrollbar */
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 1px;
            /* ซ่อน scrollbar ใน Webkit browsers (เช่น Chrome, Safari) */
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: transparent;
            /* ซ่อน thumb ของ scrollbar */
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
            /* ซ่อน track ของ scrollbar */
        }
    </style>



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

                    <div class="row mt-3 align-items-center justify-content-between col-8">
                        <div>
                            <h1 class="h3 mb-3 text-gray-800">Products</h1>
                        </div>
                        <div>
                            <input type="text" class="form-control bg-white border-0 small" onkeyup="filterCards()"
                                id="search-input" placeholder="Search for..." aria-label="Search"
                                aria-describedby="basic-addon2">
                        </div>

                    </div>


                    <div class="row">
                        <!-- ส่วนการ์ดสินค้า -->
                        <div class="col-12 col-lg-8">
                            <div class="card custom-scrollbar" style="height: 100vh; overflow-y: auto;">
                                <div class="card-body">
                                    <div class="row">
                                        <?php foreach ($results as $row) { ?>
                                            <div class="col-6 col-md-3 col-lg-2 mb-3 search-card">
                                                <!-- เปลี่ยน col-lg-4 เป็น col-lg-2 -->
                                                <div class="card" style="width: 100%; height: auto;"> <!-- ลดขนาดการ์ด -->
                                                    <img class="card-img-top" src="uploads/products/<?= $row['image'] ?>"
                                                        alt="Card image" style="width: 100%; height: 100px;">
                                                    <div class="card-body p-2"> <!-- ลด padding -->
                                                        <h5 class="card-title text-truncate mb-1">
                                                            <?= $row['product_name'] ?>
                                                        </h5>
                                                        <!-- ลดขนาดข้อความ -->
                                                        <p class="card-text text-truncate mb-0">Price :
                                                            <?= $row['product_price'] ?>
                                                        </p>
                                                        <p class="card-text text-truncate">Stock :
                                                            <?= $row['product_stock'] ?>
                                                        </p>
                                                        <!-- ตัดข้อความยาว -->
                                                        <button class="btn btn-primary btn-sm w-100"
                                                            onclick="addToCart(<?= $row['product_id'] ?>, '<?= addslashes($row['product_name']) ?>', <?= $row['product_price'] ?>, 'uploads/products/<?= $row['image'] ?>' , <?= $row['product_stock'] ?>)">Add
                                                            to Cart</button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ส่วนตะกร้าสินค้าทางขวา -->
                        <div class="col-12 col-lg-4">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Cart</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div id="cart-items" class="cart w-100"></div>
                                        </li>
                                    </ul>
                                    <div id="total-price" class="text-center"></div>
                                    <div class="row d-grid align-items-start gap-5  ">

                                        <!-- Form with Select -->
                                        <form class="mt-3">
                                            <label for="customer" class="form-label fw-bold">ลูกค้า :</label>
                                            <select name="customer" id="customer" class="form-select ml-2">
                                                <?php
                                                $sql = "SELECT * FROM customer";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute();
                                                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($results as $row) {
                                                    echo '<option value="' . $row['cust_id'] . '">' . $row['cust_name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </form>
                                    </div>

                                    <div class="d-flex justify-content-end mb-2">
                                        <button onclick="clearCart()" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i> Clear Cart
                                        </button>
                                    </div>

                                    <div class="card-footer">
                                        <button class="btn btn-success btn-block" onclick="checkout()">ชำระเงิน</button>
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

        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="login.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function filterCards() {
                var searchInput = document.getElementById("search-input");
                var filter = searchInput.value.toUpperCase();
                var cards = document.getElementsByClassName("search-card");

                for (var i = 0; i < cards.length; i++) {
                    var card = cards[i];
                    var cardText = card.textContent || card.innerText;
                    if (cardText.toUpperCase().indexOf(filter) > -1) {
                        card.style.display = "";
                    } else {
                        card.style.display = "none";
                    }
                }
            }


        </script>

        <script>
            // ฟังก์ชันเพิ่มสินค้าใน Cart
            function addToCart(id, name, price, image, stock) {
                const cart = JSON.parse(localStorage.getItem('cart')) || [];
                const existingItem = cart.find(item => item.id === id);

                if (existingItem) {
                    // ถ้ามีสินค้าตัวนี้ในตะกร้าแล้ว, เช็คว่าจำนวนสินค้าจะไม่เกินจำนวนสต็อก
                    if (existingItem.quantity >= stock) {
                        Swal.fire({
                            title: "สินค้าหมด",
                            icon: "error",
                            draggable: true
                        });
                        return;
                    }
                    existingItem.quantity += 1;
                } else {
                    // ถ้ายังไม่มีสินค้าตัวนี้ในตะกร้า, เพิ่มสินค้าลงในตะกร้า
                    if (1 > stock) {
                        Swal.fire({
                            title: "สินค้าหมด",
                            icon: "error",
                            draggable: true
                        });
                        return;
                    }
                    cart.push({ id, name, price, quantity: 1, image, stock });
                }

                // เก็บข้อมูลตะกร้ากลับลงใน localStorage
                localStorage.setItem('cart', JSON.stringify(cart));
                showprice();
                displayCart();
            }


            // ฟังก์ชันแสดงสินค้าใน Cart
            function displayCart() {
                const cart = JSON.parse(localStorage.getItem('cart')) || [];
                const cartContainer = document.getElementById('cart-items');
                cartContainer.innerHTML = '';

                if (cart.length === 0) {
                    cartContainer.innerHTML = '<p class="text-center">Your cart is empty.</p>';
                } else {
                    cart.forEach(item => {
                        cartContainer.innerHTML += `
                        <div class="cart-item card mb-3 shadow-sm w-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <img src="${item.image}" alt="${item.name}" class="img-fluid rounded mr-3" style="width: 80px; height: 80px; object-fit: cover;">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">${item.name}</h5>
                                    <p class="mb-2 text-muted">$${item.price} x ${item.quantity}</p>
                                </div>
                                <div class="btn-group justify-content-center" role="group">
                                    <button onclick="increaseQuantity(${item.id})" class="btn btn-sm btn-success">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <button onclick="decreaseQuantity(${item.id})" class="btn btn-sm btn-warning">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button onclick="removeFromCart(${item.id})" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
            `;
                    });
                }
            }

            // ฟังก์ชันลบสินค้าใน Cart
            function removeFromCart(id) {
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                cart = cart.filter(item => item.id !== id); // ลบสินค้าที่มี id ตรงกัน
                localStorage.setItem('cart', JSON.stringify(cart)); // บันทึก Cart ใหม่
                showprice();
                displayCart(); // อัปเดตการแสดง Cart
            }

            // ฟังก์ชันล้าง Cart
            function clearCart() {
                Swal.fire({
                    title: 'คุณแน่ใจหรือไม่?',
                    text: "ต้องการล้างสินค้าในตะกร้าหรือไม่?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ใช่, ล้างเลย!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ล้างตะกร้าสินค้า
                        localStorage.removeItem('cart');
                        showprice();
                        displayCart();

                        // แสดงข้อความสำเร็จ
                        Swal.fire(
                            'ล้างสำเร็จ!',
                            'ตะกร้าของคุณถูกล้างแล้ว.',
                            'success'
                        );
                    }
                });
            }


            function showprice() {
                const cart = JSON.parse(localStorage.getItem('cart')) || [];
                const cartContainer = document.getElementById('total-price');
                cartContainer.innerHTML = '';
                const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
                cartContainer.innerHTML += `<p class="text-right font-weight-bold text-primary mt-2 font-weight-bold "> Total: $${total.toFixed(2)}</p>`;
            }
            // ฟังก์ชันเพิ่มจำนวนสินค้า
            function increaseQuantity(id) {
                const cart = JSON.parse(localStorage.getItem('cart')) || [];
                const item = cart.find(item => item.id === id);

                if (item) {
                    item.quantity += 1;
                    localStorage.setItem('cart', JSON.stringify(cart));
                    displayCart();
                    showprice();
                }
            }

            // ฟังก์ชันลดจำนวนสินค้า
            function decreaseQuantity(id) {
                const cart = JSON.parse(localStorage.getItem('cart')) || [];
                const item = cart.find(item => item.id === id);

                if (item) {
                    if (item.quantity > 1) {
                        item.quantity -= 1;
                    } else {
                        // หากจำนวนเป็น 1 ให้ยืนยันการลบ
                        Swal.fire({
                            title: 'คุณแน่ใจหรือไม่?',
                            text: "ต้องการลบสินค้าชิ้นนี้ออกจากตะกร้าหรือไม่?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'ใช่, ลบเลย!',
                            cancelButtonText: 'ยกเลิก'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // ลบสินค้าหากผู้ใช้ยืนยัน
                                cart.splice(cart.indexOf(item), 1);
                                localStorage.setItem('cart', JSON.stringify(cart));
                                displayCart();
                                showprice();

                                // แจ้งเตือนว่าลบสินค้าแล้ว
                                Swal.fire(
                                    'ลบสำเร็จ!',
                                    'สินค้าถูกลบออกจากตะกร้า.',
                                    'success'
                                );
                            }
                        });
                        return; // หยุดการทำงานเพิ่มเติมจนกว่าผู้ใช้จะตอบ
                    }
                    localStorage.setItem('cart', JSON.stringify(cart));
                    displayCart();
                    showprice();
                }
            }

            function checkout(id) {
                const cart = JSON.parse(localStorage.getItem('cart')) || [];
                const customerId = document.getElementById('customer').value; // ดึงค่า Customer ID จาก input

                if (cart.length === 0) {
                    Swal.fire({
                        title: "ตะกร้าว่าง!",
                        icon: "warning",
                        draggable: true
                    });
                    return;
                }

                try {
                    // สร้างข้อมูล JSON ที่จะส่งไป
                    const payload = {
                        customerId: customerId,
                        cart: cart,
                    };

                    // สร้างฟอร์ม
                    const form = document.createElement('form');
                    form.method = 'post';
                    form.action = 'checkout.php';

                    // สร้าง input สำหรับ payload
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'payload';
                    input.value = JSON.stringify(payload); // แปลง JSON เป็น string
                    form.appendChild(input);

                    // เพิ่มฟอร์มลงใน DOM และส่งฟอร์ม
                    document.body.appendChild(form);

                    // แสดงข้อความแจ้งเตือนก่อนหน่วงเวลา
                    Swal.fire(
                        'คำสั่งซื้อเรียบร้อย',
                        'รายการถูกแอดเข้าไปในประวัติการขาย',
                        'success'
                    ).then(() => {
                        form.submit();
                        localStorage.removeItem('cart'); // ลบข้อมูลจาก localStorage
                        showprice();
                        displayCart();
                    });

                } catch (error) {
                    console.error(error);
                }
            }



            // เรียกแสดง Cart เมื่อโหลดหน้าเว็บ
            displayCart();
            showprice();


        </script>

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

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>