<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div class="main-content container-fluid">
    <h1>Calendar & Scheduling</h1>
    <div class="card">
        <div class="card-body">
            <!-- Placeholder for calendar -->
            <div id="calendar"></div>
        </div>
    </div>
</div>

<!-- Include FullCalendar CSS and JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: [
                // Fetch events from database using PHP
                { title: 'Meeting Room A', start: '2023-10-15' },
                { title: 'Conference Room B', start: '2023-10-20' }
            ]
        });
        calendar.render();
    });
</script>

<?php include 'includes/footer.php'; ?>