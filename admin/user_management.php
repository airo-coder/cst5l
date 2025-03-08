<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div class="main-content container-fluid">
    <h1>User Management</h1>

    <!-- Add User Button -->
    <a href="actions/add_user.php" class="btn btn-primary mb-3">Add New User</a>

    <!-- Users Table -->
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch all users from the database
                    $users = getUsers();
                    foreach ($users as $user) {
                        echo "<tr>
                            <td>{$user['id']}</td>
                            <td>{$user['name']}</td>
                            <td>{$user['email']}</td>
                            <td>{$user['role']}</td>
                            <td>
                                <a href='edit_user.php?id={$user['id']}' class='btn btn-sm btn-warning'>Edit</a>
                                <a href='delete_user.php?id={$user['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete</a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>