<?php 
$pageTitle = "Dashboard";
include('../includes/header.php'); 
include('../includes/navbar.php'); 
include('../includes/sidebar.php'); 
?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <?php  include('../includes/navbar.php') ?>
    <!-- End Navbar -->
    
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Your dashboard content here -->
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Today's Sales</p>
                                    <h5 class="font-weight-bolder">$53,000</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- More dashboard widgets... -->
        </div>
    </div>
    
    <?php include('../includes/footer.php'); ?>
    <?php include('../includes/scripts.php'); ?>
</main>