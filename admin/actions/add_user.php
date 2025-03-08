<?php
include '../includes/header.php';
include '../includes/sidebar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Validate input
    if (!empty($name) && !empty($email) && !empty($role)) {
 
        $conn = new mysqli('localhost', 'username', 'password', 'database_name');
 
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO users (name, email, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $role);
 
        if ($stmt->execute()) {
            echo "<script>alert('User added successfully!'); window.location.href='../user_management.php';</script>";
        } else {
            echo "<script>alert('Error adding user: " . $stmt->error . "');</script>";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "<script>alert('Please fill in all required fields.');</script>";
    }
}
?>

<div class="main-content container-fluid">
    <h1>Add New User</h1>
    <form action="add_user.php" method="POST">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select class="form-control" id="role" name="role" required>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add User</button>
        <a href="../user_management.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>