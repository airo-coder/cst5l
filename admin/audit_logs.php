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

$user = $_GET['user'] ?? '';
$action = $_GET['action'] ?? '';

$page = $_GET['page'] ?? 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$query = "
    SELECT a.id, a.action, u.name AS user_name, a.timestamp 
    FROM AuditLogs a
    JOIN Users u ON a.user_id = u.id
    WHERE 1=1
";
$params = [];

if (!empty($user)) {
    $query .= " AND u.name LIKE :user";
    $params['user'] = "%$user%";
}

if (!empty($action)) {
    $query .= " AND a.action LIKE :action";
    $params['action'] = "%$action%";
}

$query .= " ORDER BY a.timestamp DESC LIMIT :limit OFFSET :offset";
$params['limit'] = $limit;
$params['offset'] = $offset;

$stmt = $pdo->prepare($query);
foreach ($params as $key => &$value) {
    $stmt->bindParam($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$stmt->execute();
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalLogsQuery = "SELECT COUNT(*) FROM AuditLogs a JOIN Users u ON a.user_id = u.id WHERE 1=1";
if (!empty($user)) {
    $totalLogsQuery .= " AND u.name LIKE :user";
}
if (!empty($action)) {
    $totalLogsQuery .= " AND a.action LIKE :action";
}

$totalStmt = $pdo->prepare($totalLogsQuery);
if (!empty($user)) {
    $totalStmt->bindParam(':user', $user);
}
if (!empty($action)) {
    $totalStmt->bindParam(':action', $action);
}
$totalStmt->execute();
$totalLogs = $totalStmt->fetchColumn();
$totalPages = ceil($totalLogs / $limit);
?>

<div class="main-content container-fluid">
    <h1>Audit Logs</h1>

    <form method="GET" class="mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <label for="user" class="form-label">User</label>
                <input type="text" name="user" id="user" class="form-control" placeholder="Search by user">
            </div>
            <div class="col-md-4">
                <label for="action" class="form-label">Action</label>
                <input type="text" name="action" id="action" class="form-control" placeholder="Search by action">
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
                        <th>Log ID</th>
                        <th>Action</th>
                        <th>User</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= htmlspecialchars($log['id']) ?></td>
                        <td><?= htmlspecialchars($log['action']) ?></td>
                        <td><?= htmlspecialchars($log['user_name']) ?></td>
                        <td><?= htmlspecialchars($log['timestamp']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <nav aria-label="Audit Logs Pagination" class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&user=<?= $user ?>&action=<?= $action ?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>