<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div class="main-content container-fluid">
    <h1>Dashboard</h1>
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Rooms</h5>
                    <p class="card-text">25</p> <!-- Fetch from database using PHP -->
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Bookings</h5>
                    <p class="card-text">120</p> <!-- Fetch from database using PHP -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>