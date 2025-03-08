<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div class="main-content container-fluid">
    <h1>Booking Management</h1>
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Booking ID</th>
                <th>Room Number</th>
                <th>Date</th>
                <th>Timeslot</th>
                <th>Subject</th>
                <th>Purpose</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Fetch bookings from database using PHP -->
            <tr>
                <td>101</td>
                <td>106</td>
                <td>2025-03-05</td>
                <td>John Doe</td>
                <td>Math</td>
                <td>Group Discussion</td>
                <td>
                    <a href="#" class="btn btn-sm btn-success">Approve</a>
                    <a href="#" class="btn btn-sm btn-danger">Reject</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>