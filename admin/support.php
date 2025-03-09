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

$stmt = $pdo->query("SELECT * FROM FAQs ORDER BY created_at DESC");
$faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'] ?? '';

    if (empty($message)) {
        $error = "Please enter a message.";
    } else {
        $stmt = $pdo->prepare("SELECT name, email FROM Users WHERE id = :user_id");
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("
            INSERT INTO SupportMessages (user_id, message)
            VALUES (:user_id, :message)
        ");
        $stmt->execute([
            'user_id' => $_SESSION['user_id'],
            'message' => $message,
        ]);

        $to = "support@umindanao.edu.ph";
        $subject = "New Support Message";
        $body = "A new support message has been submitted by {$user['name']} ({$user['email']}):\n\n$message";
        $headers = "From: no-reply@umindanao.edu.ph";

        if (mail($to, $subject, $body, $headers)) {
            $success = "Your message has been sent! We will get back to you soon.";
        } else {
            $error = "Failed to send your message. Please try again later.";
        }
    }
}
?>

<div class="main-content container-fluid">
    <h1>Support & Help</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">FAQs</h5>
            <ul>
                <?php foreach ($faqs as $faq): ?>
                <li>
                    <strong><?= htmlspecialchars($faq['question']) ?></strong><br>
                    <?= htmlspecialchars($faq['answer']) ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title">Contact Support</h5>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php elseif (isset($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>