<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div class="main-content container-fluid">
    <h1>Settings</h1>
    <div class="card">
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label for="systemName" class="form-label">System Name</label>
                    <input type="text" class="form-control" id="systemName" value="Collaboration Room Reservation">
                </div>
                <div class="mb-3">
                    <label for="timezone" class="form-label">Timezone</label>
                    <select class="form-select" id="timezone">
                        <option value="UTC">UTC</option>
                        <option value="EST">EST</option>
                        <option value="PST">PST</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>