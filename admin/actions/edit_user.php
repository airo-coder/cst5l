<?php
include '../includes/header.php';
include '../includes/sidebar.php';

if (!isset($_GET['id'])) {
    echo "<script>alert('User ID not provided.'); window.location.href='../user_management.php';</script>";
    exit();
}

$user_id = $_GET['id'];

$conn = new mysqli('localhost', 'username', 'password', 'database_name');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    if (!empty($name) && !empty($email) && !empty($role)) {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $role, $user_id);

        if ($stmt->execute()) {
            echo "<script>alert('User updated successfully!'); window.location.href='../user_management.php';</script>";
        } else {
            echo "<script>alert('Error updating user: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Please fill in all required fields.');</script>";
    }
}

$conn->close();
?>

<div class="main-content container-fluid">
    <h1>Edit User</h1>
    <form action="edit_user.php?id=<?php echo $user_id; ?>" method="POST">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select class="form-control" id="role" name="role" required>
                <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="user" <?php echo ($user['role'] == 'user') ? 'selected' : ''; ?>>User</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="../user_management.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>