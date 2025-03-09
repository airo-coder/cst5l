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

$stmt = $pdo->query("
    SELECT b.id, r.room_number, b.date, b.timeslot, b.subject, b.purpose, b.status 
    FROM Bookings b
    JOIN Rooms r ON b.room_id = r.id
    WHERE b.status = 'approved'
    ORDER BY b.date ASC, b.timeslot ASC
");
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$events = [];
foreach ($bookings as $booking) {
    $events[] = [
        'id' => $booking['id'],
        'title' => "Room {$booking['room_number']} - {$booking['subject']}",
        'start' => $booking['date'],
        'description' => "Purpose: {$booking['purpose']}<br>Timeslot: {$booking['timeslot']}",
        'status' => $booking['status'],
    ];
}
?>

<div class="main-content container-fluid">
    <h1>Calendar & Scheduling</h1>
    <div class="card">
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: <?= json_encode($events) ?>,
            eventContent: function(arg) {
                return {
                    html: `<div class="fc-event-title">${arg.event.title}</div>
                           <div class="fc-event-description">${arg.event.extendedProps.description}</div>`
                };
            },
            eventClick: function(info) {
                alert(`Booking ID: ${info.event.id}\nRoom: ${info.event.title}\nDate: ${info.event.start.toLocaleDateString()}\nPurpose: ${info.event.extendedProps.description}`);
            },
            eventDidMount: function(info) {
                if (info.event.extendedProps.status === 'pending') {
                    info.el.classList.add('fc-event-pending');
                } else if (info.event.extendedProps.status === 'rejected') {
                    info.el.classList.add('fc-event-rejected');
                }
            }
        });
        calendar.render();
    });
</script>

<style>
    .fc-event-pending {
        background-color: #ffc107;
        border-color: #ffc107;
    }
    .fc-event-rejected {
        background-color: #dc3545;
        border-color: #dc3545;
    }
</style>

<?php include 'includes/footer.php'; ?>