<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div class="main-content container-fluid">
    <h1>Support & Help</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">FAQs</h5>
            <ul>
                <li>How do I book a room?</li>
                <li>How do I cancel a booking?</li>
                <li>How do I contact support?</li>
            </ul>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title">Contact Support</h5>
            <form>
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control" id="message" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>