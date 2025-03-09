<?php
session_start();
include 'includes/header.php';
include 'includes/sidebar.php';

include 'includes/db_connection.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$name = $_GET['name'] ?? '';
$email = $_GET['email'] ?? '';
$role = $_GET['role'] ?? '';

$page = $_GET['page'] ?? 1;
$limit = 10; 
$offset = ($page - 1) * $limit;

$query = "SELECT id, name, email, role FROM Users WHERE 1=1";
$params = [];

if (!empty($name)) {
    $query .= " AND name LIKE :name";
    $params['name'] = "%$name%";
}

if (!empty($email)) {
    $query .= " AND email LIKE :email";
    $params['email'] = "%$email%";
}

if (!empty($role)) {
    $query .= " AND role = :role";
    $params['role'] = $role;
}

$query .= " ORDER BY id ASC LIMIT :limit OFFSET :offset";
$params['limit'] = $limit;
$params['offset'] = $offset;

$stmt = $pdo->prepare($query);
foreach ($params as $key => &$value) {
    $stmt->bindParam($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalUsersQuery = "SELECT COUNT(*) FROM Users WHERE 1=1";
if (!empty($name)) {
    $totalUsersQuery .= " AND name LIKE :name";
}
if (!empty($email)) {
    $totalUsersQuery .= " AND email LIKE :email";
}
if (!empty($role)) {
    $totalUsersQuery .= " AND role = :role";
}

$totalStmt = $pdo->prepare($totalUsersQuery);
if (!empty($name)) {
    $totalStmt->bindParam(':name', $name);
}
if (!empty($email)) {
    $totalStmt->bindParam(':email', $email);
}
if (!empty($role)) {
    $totalStmt->bindParam(':role', $role);
}
$totalStmt->execute();
$totalUsers = $totalStmt->fetchColumn();
$totalPages = ceil($totalUsers / $limit);
?>

<div class="main-content container-fluid">
    <h1>User Management</h1>

    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
        Add New User
    </button>

    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm" action="actions/add_user.php" method="POST">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="addUserForm" class="btn btn-primary">Add User</button>
                </div>
            </div>
        </div>
    </div>

<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" action="actions/edit_user.php" method="POST">
                    <input type="hidden" name="id" id="editUserId">
                    <div class="form-group">
                        <label for="editName">Name</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="editEmail">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="editRole">Role</label>
                        <select class="form-control" id="editRole" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <form method="GET" class="mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Search by name">
            </div>
            <div class="col-md-4">
                <label for="email" class="form-label">Email</label>
                <input type="text" name="email" id="email" class="form-control" placeholder="Search by email">
            </div>
            <div class="col-md-4">
                <label for="role" class="form-label">Role</label>
                <select name="role" id="role" class="form-select">
                    <option value="">All</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100 mt-4">Filter</button>
            </div>
        </div>
    </form>

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
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <a href="#editUserModal" class="btn btn-sm btn-warning edit-user-btn" data-id="<?= $user['id'] ?>">Edit</a>
                            <a href="actions/delete_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <nav aria-label="Users Pagination" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>&name=<?= $name ?>&email=<?= $email ?>&role=<?= $role ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function () {
    $('.edit-user-btn').on('click', function () {
        var userId = $(this).data('id');
        var row = $(this).closest('tr');
        var name = row.find('td:eq(1)').text();
        var email = row.find('td:eq(2)').text();
        var role = row.find('td:eq(3)').text();

        $('#editUserId').val(userId);
        $('#editName').val(name);
        $('#editEmail').val(email);
        $('#editRole').val(role);

        $('#editUserModal').modal('show');
    });
});
</script>

<?php include 'includes/footer.php'; ?>