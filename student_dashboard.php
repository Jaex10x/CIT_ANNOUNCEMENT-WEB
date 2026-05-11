<?php
session_start();
if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'student') {
    header("Location: login.php");
    exit();
}


include 'connect.php';
require_once 'includes/header.php';

$query = "SELECT * FROM announcements ORDER BY id DESC";
$resultset = $connection->query($query);
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
    .announcement-card {
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .announcement-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }
    .announcement-title { 
        font-size: 1.3rem; 
        font-weight: bold; 
        color: #a3262a; 
        margin-bottom: 10px; 
    }
    .announcement-date { 
        color: #666; 
        font-size: 0.85rem; 
        margin-bottom: 15px;
        border-left: 3px solid #a3262a;
        padding-left: 12px;
    }
    .announcement-content { 
        color: #333; 
        font-size: 0.95rem; 
        margin-bottom: 15px; 
        line-height: 1.6; 
    }
    .divider { 
        border-top: 1px solid #e0e0e0; 
        margin: 15px 0; 
    }
    .feed-header {
        background-color: #ffd700;
        display: inline-block;
        padding: 8px 24px;
        border-radius: 30px;
        color: #a3262a;
        font-weight: bold;
        margin-bottom: 30px;
    }
    .cit-header { 
        text-align: center; 
        margin-bottom: 30px; 
    }
    .cit-header h1 { 
        color: #800000; 
        font-weight: bold; 
        font-size: 1.8rem; 
        margin-bottom: 5px; 
    }
    .cit-header p { 
        color: #666; 
        font-size: 0.9rem; 
    }
    .welcome-banner {
        background: linear-gradient(135deg, #a3262a 0%, #800000 100%);
        color: white;
        padding: 20px 25px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .welcome-banner h3 {
        margin: 0;
        font-size: 1.3rem;
    }
    .welcome-banner p {
        margin: 5px 0 0;
        opacity: 0.9;
        font-size: 0.85rem;
    }
    .logout-btn {
        background-color: transparent;
        border: 2px solid white;
        color: white;
        padding: 8px 20px;
        border-radius: 30px;
        transition: all 0.3s ease;
    }
    .logout-btn:hover {
        background-color: white;
        color: #800000;
        text-decoration: none;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #f9f9f9;
        border-radius: 15px;
    }
    .empty-state i {
        font-size: 48px;
        color: #ccc;
        margin-bottom: 15px;
    }
    .empty-state p {
        color: #999;
        font-size: 1rem;
    }
    footer {
        text-align: center;
        margin-top: 40px;
        padding: 20px;
        border-top: 1px solid #ddd;
        color: #666;
        font-size: 0.8rem;
    }
</style>

<div class="container mt-4">
    <div class="cit-header">
        <h1>CIT UNIVERSITY ADVISORY</h1>
        <p>Official Announcements for Students</p>
    </div>

    <div class="welcome-banner d-flex justify-content-between align-items-center">
        <div>
            <h3>Welcome, <?= htmlspecialchars($_SESSION['email'] ?? 'Student') ?>! 📢</h3>
            <p>Stay updated with the latest university announcements</p>
        </div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="feed-header">📢 Announcement Feed</div>
        <small class="text-muted">Read-only access</small>
    </div>

    <?php if ($resultset->num_rows == 0): ?>
        <div class="empty-state">
            <i>📭</i>
            <p>No announcements available at the moment.</p>
            <small class="text-muted">Check back later for updates from the administration.</small>
        </div>
    <?php endif; ?>

    <?php while ($row = $resultset->fetch_assoc()): ?>
        <div class="announcement-card">
            <div class="announcement-title">📌 <?= htmlspecialchars($row['title']) ?></div>
            <div class="announcement-date">
                📅 Posted on: <?= date('F j, Y', strtotime($row['date_posted'])) ?>
            </div>
            <div class="announcement-content">
                <?= nl2br(htmlspecialchars($row['announcement_text'])) ?>
            </div>
            <div class="divider"></div>
            <div>
                <span class="status-badge status-active">✓ OFFICIAL ANNOUNCEMENT</span>
                <small class="text-muted ml-2">Source: CIT-U Administration</small>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<footer>
    <small>Cebu Institute of Technology - University</small>
</footer>

<?php require_once 'includes/footer.php'; ?>