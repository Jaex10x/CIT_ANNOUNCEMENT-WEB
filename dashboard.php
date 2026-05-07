<?php   
    include 'connect.php';
    // Fetch newest announcements first
    $query = "SELECT * FROM announcements ORDER BY id DESC"; 
    $resultset = $connection->query($query);
    
    require_once 'includes/header.php'; 
?>

<div class="container mt-4 text-center">
    <div class="d-flex align-items-center justify-content-center gap-3 mb-4">
        <h2 style="color: #800000; margin-bottom: 0;">Announcement Dashboard</h2>
        <a href="addNewAnnouncement.php" class="btn btn-danger rounded-pill px-3 py-1" style="background-color: #a3262a; border: none; font-size: 0.8rem;">
            + Create Announcement
        </a>
        <a href="logout.php" class="btn btn-danger rounded-pill px-3 py-1" style="background-color: #a3262a; border: none; font-size: 0.8rem;">
            Logout
        </a>
    </div>

    <h4 class="p-2 mb-4" style="background-color: #ffd700; border-radius: 25px; color: #a3262a; font-weight: bold; width: 100%;">
        Announcement Feed
    </h4>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <?php while($row = $resultset->fetch_assoc()): ?>
                <div class="card mb-3 shadow-sm border-0 text-start" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold">Title: <?php echo htmlspecialchars($row['title']); ?></h5>
                        <p class="text-muted mb-2">Date: <?php echo htmlspecialchars($row['date_posted']); ?></p>
                        <p><?php echo nl2br(htmlspecialchars($row['announcement_text'])); ?></p>
                        
                        <div class="mt-3">
                            <a href="update.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger rounded-pill px-3" style="background-color: #a3262a;">update</a>
                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger rounded-pill px-3" style="background-color: #a3262a;">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>