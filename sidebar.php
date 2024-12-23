<?php

session_start();

if (isset($_GET['logout'])) {
    session_unset();  // ลบข้อมูลใน session
    session_destroy();  // ทำลาย session
    header('Location: login.php');  // เปลี่ยนเส้นทางไปยังหน้า login
    exit();
}

function renderSidebar($activePage)
{
    ?>
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
            <div class="sidebar-brand-icon">
                <i class="fas fa-calculator"></i>
            </div>
            <div class="sidebar-brand-text mx-5">POS</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item <?= $activePage == 'dashboard' ? 'active' : '' ?>">
            <a class="nav-link" href="index.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item <?= $activePage == 'cart' ? 'active' : '' ?>">
            <a class="nav-link" href="cart.php">
                <i class="fas fa-cart-plus"></i>
                <span>Cart</span>
            </a>
        </li>
        <li class="nav-item <?= $activePage == 'sales' ? 'active' : '' ?>">
            <a class="nav-link" href="sales.php">
                <i class="fas fa-table"></i>
                <span>Sales</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">Manage</div>

        <!-- Nav Item - Components -->
        <li class="nav-item <?= $activePage == 'components' ? 'active' : '' ?>">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseComponents"
                aria-expanded="true" aria-controls="collapseComponents">
                <i class="fas fa-fw fa-cog"></i>
                <span>Manager</span>
            </a>
            <div id="collapseComponents" class="collapse <?= $activePage == 'components' ? 'show' : '' ?>"
                aria-labelledby="headingComponents" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Topic :</h6>
                    <a class="collapse-item" href="add.php">Manage products</a>
                    <a class="collapse-item" href="cards.php">Stocks</a>
                </div>
            </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Nav Item - Logout -->
        <li class="nav-item">
            <a class="nav-link" href="?logout=true">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>

    </ul>
    <?php
}
?>