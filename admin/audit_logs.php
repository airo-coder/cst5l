<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div class="main-content container-fluid">
    <h1>Audit Logs</h1>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Log ID</th>
                        <th>Action</th>
                        <th>User</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Fetch logs from database using PHP -->
                    <tr>
                        <td>1</td>
                        <td>Room Booked</td>
                        <td>Admin</td>
                        <td>2023-10-15 10:00 AM</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Room Cancelled</td>
                        <td>User</td>
                        <td>2023-10-16 11:00 AM</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>