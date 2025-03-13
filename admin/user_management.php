<?php
session_start();
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/db_connection.php';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$id = $_GET['id'] ?? '';
$name = $_GET['name'] ?? '';
$role = $_GET['role'] ?? '';

$page = $_GET['page'] ?? 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$query = "SELECT id, name, email, role, profile_image FROM Users WHERE 1=1";
$types = '';
$params = [];

if (!empty($id)) {
    $query .= " AND id = ?";
    $types .= 'i';
    $params[] = $id;
}

if (!empty($name)) {
    $query .= " AND name LIKE ?";
    $types .= 's';
    $params[] = "%$name%";
}

if (!empty($role)) {
    $query .= " AND role = ?";
    $types .= 's';
    $params[] = $role;
}

$query .= " ORDER BY id ASC LIMIT ? OFFSET ?";
$types .= 'ii';
$params[] = $limit;
$params[] = $offset;

$stmt = $conn->prepare($query);
if ($stmt) {
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
} else {
    die("Error preparing query: " . $conn->error);
}

$totalUsersQuery = "SELECT COUNT(*) FROM Users WHERE 1=1";
$totalParams = [];
$totalTypes = '';

if (!empty($id)) {
    $totalUsersQuery .= " AND id = ?";
    $totalTypes .= 'i';
    $totalParams[] = $id;
}

if (!empty($name)) {
    $totalUsersQuery .= " AND name LIKE ?";
    $totalTypes .= 's';
    $totalParams[] = "%$name%";
}

if (!empty($role)) {
    $totalUsersQuery .= " AND role = ?";
    $totalTypes .= 's';
    $totalParams[] = $role;
}

$totalStmt = $conn->prepare($totalUsersQuery);
if ($totalStmt) {
    if (!empty($totalParams)) {
        $totalStmt->bind_param($totalTypes, ...$totalParams);
    }
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalUsers = $totalResult->fetch_row()[0];
    $totalPages = ceil($totalUsers / $limit);
} else {
    die("Error preparing total users query: " . $conn->error);
}

$stmt->close();
$totalStmt->close();
$conn->close();
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
                    <form id="addUserForm" action="actions/add_user.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="add_name">Name</label>
                            <input type="text" class="form-control" id="add_name" name="add_name" required>
                        </div>
                        <div class="form-group">
                            <label for="add_email">Email</label>
                            <input type="email" class="form-control" id="add_email" name="add_email" required>
                        </div>
                        <div class="form-group">
                            <label for="add_password">Password</label>
                            <input type="password" class="form-control" id="add_password" name="add_password" required>
                        </div>
                        <div class="form-group">
                            <label for="add_role">Role</label>
                            <select class="form-control" id="add_role" name="add_role" required>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="add_profile_image">Profile Image</label>
                            <input type="file" class="form-control" id="add_profile_image" name="profile_image" accept="image/jpeg, image/png">
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

    <form method="GET" class="mb-4" id="filterForm">
        <div class="row g-3">
            <div class="col-md-4">
                <label for="id" class="form-label">ID</label>
                <input type="number" name="id" id="id" class="form-control" placeholder="Search by ID" value="<?= htmlspecialchars($id ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Search by name" value="<?= htmlspecialchars($name ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label for="role" class="form-label">Role</label>
                <select name="role" id="role" class="form-select">
                    <option value="">All</option>
                    <option value="admin" <?= ($role ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="user" <?= ($role ?? '') === 'user' ? 'selected' : '' ?>>User</option>
                </select>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-body">
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th style="width: 120px;">Profile Image</th>
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
                    <td class="align-middle text-center" style="vertical-align: middle;">
                        <?php if (!empty($user['profile_image'])): ?>
                            <img src="../images/profiles/<?= htmlspecialchars($user['profile_image']) ?>"
                                alt="Profile Image"
                                width="40"
                                height="40"
                                class="rounded-circle object-fit-cover"
                                style="object-fit: cover;">
                        <?php else: ?>
                            <img src="../images/profiles/default-profile.jpg"
                                alt="Default Profile Image"
                                width="40"
                                height="40"
                                class="rounded-circle object-fit-cover"
                                style="object-fit: cover;">
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td>
                        <a href="#editUserModal" class="btn btn-sm btn-warning edit-user-btn"
                            data-bs-toggle="modal"
                            data-id="<?= $user['id'] ?>"
                            data-name="<?= htmlspecialchars($user['name']) ?>"
                            data-email="<?= htmlspecialchars($user['email']) ?>"
                            data-role="<?= htmlspecialchars($user['role']) ?>">
                            Edit
                        </a>
                        <a href="actions/delete_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
                    <form id="editUserForm" action="actions/edit_user.php" method="POST" enctype="multipart/form-data">
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
                        <div class="form-group">
                            <label for="editProfileImage">Profile Image</label>
                            <input type="file" class="form-control" id="editProfileImage" name="profile_image" accept="image/jpeg, image/png">
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

    <nav aria-label="Users Pagination" class="mt-4">
    <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= $page == $i ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>&id=<?= $id ?>&name=<?= $name ?>&role=<?= $role ?>"><?= $i ?></a>
        </li>
        <?php endfor; ?>
    </ul>
</nav>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const editButtons = document.querySelectorAll(".edit-user-btn");

        editButtons.forEach(function (button) {
            button.addEventListener("click", function () {
                const userId = this.getAttribute("data-id");
                const userName = this.getAttribute("data-name");
                const userEmail = this.getAttribute("data-email");
                const userRole = this.getAttribute("data-role");

                document.getElementById("editUserId").value = userId;
                document.getElementById("editName").value = userName;
                document.getElementById("editEmail").value = userEmail;
                document.getElementById("editRole").value = userRole;
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('filterForm');
    const idInput = document.getElementById('id');
    const nameInput = document.getElementById('name');
    const roleSelect = document.getElementById('role');

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    function submitForm() {
        filterForm.submit();
    }

    const debouncedSubmitForm = debounce(submitForm, 500);

    idInput.addEventListener('input', debouncedSubmitForm);
    nameInput.addEventListener('input', debouncedSubmitForm);
    roleSelect.addEventListener('change', submitForm);
});
</script>

<?php include 'includes/footer.php'; ?>