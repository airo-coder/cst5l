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

$stmt = $pdo->query("SELECT setting_key, setting_value FROM Settings");
$settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$systemName = $settings['system_name'] ?? 'Collaboration Room Reservation';
$timezone = $settings['timezone'] ?? 'UTC';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $systemName = $_POST['systemName'] ?? '';
    $timezone = $_POST['timezone'] ?? '';
    $maxBookingDuration = $_POST['maxBookingDuration'] ?? '';
    $bookingWindow = $_POST['bookingWindow'] ?? '';
    $defaultCapacity = $_POST['defaultCapacity'] ?? '';
    $emailNotifications = isset($_POST['emailNotifications']) ? '1' : '0';

    if (empty($systemName) || empty($timezone) || empty($maxBookingDuration) || empty($bookingWindow) || empty($defaultCapacity)) {
        $error = "Please fill in all fields.";
    } else {
        $settingsToSave = [
            'system_name' => $systemName,
            'timezone' => $timezone,
            'max_booking_duration' => $maxBookingDuration,
            'booking_window' => $bookingWindow,
            'default_capacity' => $defaultCapacity,
            'email_notifications' => $emailNotifications,
        ];

        foreach ($settingsToSave as $key => $value) {
            $stmt = $pdo->prepare("
                INSERT INTO Settings (setting_key, setting_value)
                VALUES (:key, :value)
                ON DUPLICATE KEY UPDATE setting_value = :value
            ");
            $stmt->execute(['key' => $key, 'value' => $value]);
        }

        $success = "Settings saved successfully!";
    }
}
?>

<div class="main-content container-fluid">
    <h1>Settings</h1>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif (isset($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label for="systemName" class="form-label">System Name</label>
                    <input type="text" class="form-control" id="systemName" name="systemName" value="<?= htmlspecialchars($systemName) ?>">
                </div>
                <div class="mb-3">
                    <label for="timezone" class="form-label">Timezone</label>
                    <select class="form-select" id="timezone" name="timezone">
                        <option value="UTC" <?= $timezone === 'UTC' ? 'selected' : '' ?>>UTC</option>
                        <option value="EST" <?= $timezone === 'EST' ? 'selected' : '' ?>>EST</option>
                        <option value="PST" <?= $timezone === 'PST' ? 'selected' : '' ?>>PST</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="maxBookingDuration" class="form-label">Maximum Booking Duration (hours)</label>
                    <input type="number" class="form-control" id="maxBookingDuration" name="maxBookingDuration" value="<?= htmlspecialchars($settings['max_booking_duration'] ?? 4) ?>">
                </div>
                <div class="mb-3">
                    <label for="bookingWindow" class="form-label">Booking Window (days)</label>
                    <input type="number" class="form-control" id="bookingWindow" name="bookingWindow" value="<?= htmlspecialchars($settings['booking_window'] ?? 14) ?>">
                </div>
                <div class="mb-3">
                    <label for="defaultCapacity" class="form-label">Default Room Capacity</label>
                    <input type="number" class="form-control" id="defaultCapacity" name="defaultCapacity" value="<?= htmlspecialchars($settings['default_capacity'] ?? 10) ?>">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="emailNotifications" name="emailNotifications" <?= isset($settings['email_notifications']) && $settings['email_notifications'] === '1' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="emailNotifications">Enable Email Notifications</label>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>