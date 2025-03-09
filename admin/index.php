<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div class="main-content container-fluid">
    <h1>Dashboard</h1>

    <!-- Quick Stats Overview -->
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Bookings Today</h5>
                    <p class="card-text">12</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Active Bookings</h5>
                    <p class="card-text">5</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Rooms Available</h5>
                    <p class="card-text">8</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Pending Actions</h5>
                    <p class="card-text">3</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Bookings -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Upcoming Bookings</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Room</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Booked By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Room 101</td>
                                <td>2023-10-15</td>
                                <td>10:00 AM</td>
                                <td>John Doe</td>
                            </tr>
                            <tr>
                                <td>Room 102</td>
                                <td>2023-10-15</td>
                                <td>2:00 PM</td>
                                <td>Jane Smith</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Recent Activities</h5>
                    <ul class="list-group">
                        <li class="list-group-item">[2023-10-15 10:00 AM] Room 101 booked by John Doe.</li>
                        <li class="list-group-item">[2023-10-15 11:00 AM] User Jane Smith registered.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>