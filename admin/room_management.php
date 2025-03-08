<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div class="main-content container-fluid">
    <h1>Room Management</h1>
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Room Number</th>
                <th>Capacity</th>
                <th>Equipment</th>
                <th>Floor</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Fetch rooms from database using PHP -->
            <tr>
                <td>106</td>
                <td>10 people</td>
                <td>TV, Chairs</td>
                <td>1st</td>
                <td>Collaboration Room</td>
                <td>
                    <a href="#" class="btn btn-sm btn-warning">Edit</a>
                    <a href="#" class="btn btn-sm btn-danger">Delete</a>
                </td>
            </tr>
        </tbody>
    </table>
    <a href="actions/add_room.php" class="btn btn-primary">Add New Room</a>
</div>

<?php include 'includes/footer.php'; ?>