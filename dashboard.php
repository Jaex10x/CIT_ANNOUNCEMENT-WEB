<?php
session_start();
if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'admin') {
    header("Location: login.php");
    exit();
}
include 'connect.php';

// Pagination settings
$limit        = 3; // announcements per page
$page         = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$page         = max(1, $page);
$offset       = ($page - 1) * $limit;

// Total count for page calculation
$countResult  = $connection->query("SELECT COUNT(*) AS total FROM announcements");
$totalRows    = $countResult->fetch_assoc()['total'];
$totalPages   = ceil($totalRows / $limit);
$page         = min($page, max(1, $totalPages)); // clamp after we know total

$stmt = $connection->prepare("SELECT * FROM announcements ORDER BY id DESC LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$resultset = $stmt->get_result();

require_once 'includes/header.php';
?>

<style>
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        margin-right: 8px;
        margin-bottom: 8px;
    }
    .status-active  { background-color: #28a745; color: white; }
    .status-unread  { background-color: #ffc107; color: #333; }
    .status-review  { background-color: #17a2b8; color: white; }
    .status-reset   { background-color: #6c757d; color: white; }
    .announcement-card {
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .announcement-title  { font-size: 1.3rem; font-weight: bold; color: #333; margin-bottom: 10px; }
    .announcement-date   { color: #666; font-size: 0.85rem; margin-bottom: 15px; }
    .announcement-contact{ color: #555; font-size: 0.9rem; margin-bottom: 15px; line-height: 1.4; }
    .divider             { border-top: 1px solid #e0e0e0; margin: 15px 0; }
    .feed-header {
        background-color: #ffd700;
        display: inline-block;
        padding: 8px 24px;
        border-radius: 30px;
        color: #a3262a;
        font-weight: bold;
        margin-bottom: 30px;
    }
    .cit-header            { text-align: center; margin-bottom: 30px; }
    .cit-header h1         { color: #800000; font-weight: bold; font-size: 1.8rem; margin-bottom: 5px; }
    .cit-header p          { color: #666; font-size: 0.9rem; }

    /* Pagination */
    .pagination-wrap {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 6px;
        margin: 30px 0 10px;
        flex-wrap: wrap;
    }
    .page-info {
        text-align: center;
        color: #666;
        font-size: 0.85rem;
        margin-bottom: 30px;
    }
    .page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 10px;
        border-radius: 8px;
        border: 1px solid #ddd;
        background: white;
        color: #a3262a;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }
    .page-btn:hover {
        background-color: #a3262a;
        color: white;
        border-color: #a3262a;
        text-decoration: none;
    }
    .page-btn.active {
        background-color: #a3262a;
        color: white;
        border-color: #a3262a;
        pointer-events: none;
    }
    .page-btn.disabled {
        color: #ccc;
        pointer-events: none;
        border-color: #eee;
    }
</style>

<div class="container mt-4">
    <div class="cit-header">
        <h1>CIT UNIVERSITY ADVISORY</h1>
        <p>Announcement Management Dashboard</p>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="feed-header">Announcement Feed</div>
        <div>
            <a href="manage_users.php" class="btn btn-outline-danger rounded-pill px-4 py-2 mr-2"
               style="border-color:#a3262a; color:#a3262a;">
                Manage Users
            </a>
            <a href="addNewAnnouncement.php" class="btn btn-danger rounded-pill px-4 py-2"
               style="background-color:#a3262a; border:none;">
                + Create Announcement
            </a>
        </div>
    </div>

    <?php if ($totalRows == 0): ?>
        <div class="text-center py-5">
            <p class="text-muted">No announcements yet. Click "Create Announcement" to add one.</p>
        </div>
    <?php endif; ?>

    <?php while ($row = $resultset->fetch_assoc()): ?>
        <div class="announcement-card">
            <div class="announcement-title"><?= htmlspecialchars($row['title']) ?></div>
            <div class="announcement-date">Date: <?= htmlspecialchars($row['date_posted']) ?></div>
            <div class="announcement-contact">
                <?= nl2br(htmlspecialchars($row['announcement_text'])) ?>
            </div>
            <div class="divider"></div>
            <div>
                <span class="status-badge status-active">ACTIVE</span>
                <span class="status-badge status-unread">UNREAD</span>
                <span class="status-badge status-review">REVIEW</span>
                <span class="status-badge status-reset">RESET</span>
            </div>
            <div class="mt-3">
                <a href="update.php?id=<?= $row['id'] ?>"
                   class="btn btn-sm btn-outline-danger rounded-pill px-3"
                   style="border-color:#a3262a; color:#a3262a;">Edit</a>
                <a href="delete.php?id=<?= $row['id'] ?>"
                   class="btn btn-sm btn-outline-danger rounded-pill px-3 ml-2"
                   style="border-color:#a3262a; color:#a3262a;"
                   onclick="return confirm('Are you sure you want to delete this announcement?');">
                   Delete
                </a>
            </div>
        </div>
    <?php endwhile; ?>

    <?php if ($totalPages > 1): ?>
        <div class="pagination-wrap">
            <!-- Previous -->
            <a href="?page=<?= $page - 1 ?>"
               class="page-btn <?= $page <= 1 ? 'disabled' : '' ?>">
                &laquo; Prev
            </a>

            <?php
            // Show up to 5 page buttons with ellipsis
            $range = 2;
            $start = max(1, $page - $range);
            $end   = min($totalPages, $page + $range);

            if ($start > 1): ?>
                <a href="?page=1" class="page-btn">1</a>
                <?php if ($start > 2): ?>
                    <span class="page-btn disabled">&hellip;</span>
                <?php endif; ?>
            <?php endif; ?>

            <?php for ($i = $start; $i <= $end; $i++): ?>
                <a href="?page=<?= $i ?>"
                   class="page-btn <?= $i === $page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($end < $totalPages): ?>
                <?php if ($end < $totalPages - 1): ?>
                    <span class="page-btn disabled">&hellip;</span>
                <?php endif; ?>
                <a href="?page=<?= $totalPages ?>" class="page-btn"><?= $totalPages ?></a>
            <?php endif; ?>

            <!-- Next -->
            <a href="?page=<?= $page + 1 ?>"
               class="page-btn <?= $page >= $totalPages ? 'disabled' : '' ?>">
                Next &raquo;
            </a>
        </div>

        <div class="page-info">
            Showing page <?= $page ?> of <?= $totalPages ?>
            &nbsp;&bull;&nbsp;
            <?= $totalRows ?> total announcement<?= $totalRows !== 1 ? 's' : '' ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>