<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CIT-U Announcements</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Lato', sans-serif; background-color: #f5f5f5; }
        .navbar { background-color: #800000 !important; }
        .navbar-brand, .nav-link { color: #fff !important; }
        .nav-link:hover { color: #ffd700 !important; }
    </style>
</head>
<body>

<?php if (isset($_SESSION['userType'])): ?>
<nav class="navbar navbar-expand-lg">
    <a class="navbar-brand font-weight-bold" href="dashboard.php">CIT-U ADVISORY</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">
            <?php if ($_SESSION['userType'] === 'admin'): ?>
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_users.php">Manage Users</a></li>
                <li class="nav-item"><a class="nav-link" href="addNewAnnouncement.php">+ Announcement</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>
<?php endif; ?>
