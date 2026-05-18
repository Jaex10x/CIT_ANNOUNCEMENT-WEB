<?php
session_start();
if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'admin') {
    header("Location: login.php");
    exit();
}
include 'connect.php';
require_once 'includes/header.php';

if (isset($_POST['btnSave'])) {
    $title = trim($_POST['txtTitle']);
    $date  = $_POST['txtDate'];
    $text  = trim($_POST['txtContent']);

    $stmt = $connection->prepare(
        "INSERT INTO announcements (title, date_posted, announcement_text) VALUES (?, ?, ?)"
    );
    $stmt->bind_param("sss", $title, $date, $text);

    if ($stmt->execute()) {
       header("Location: dashboard.php?success=1");
        exit();
    } else {
        echo "Failed to publish announcement.";
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow border-0" style="border-radius: 20px;">
                <div class="card-header text-white text-center"
                     style="background-color: #a3262a; border-radius: 20px 20px 0 0;">
                    <h4 class="mb-0">Create Announcement</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        <div class="form-group">
                            <label class="font-weight-bold">Title</label>
                            <input type="text" name="txtTitle" class="form-control" placeholder="Announcement title" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Date</label>
                            <input type="date" name="txtDate" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Content</label>
                            <textarea name="txtContent" class="form-control" rows="5"
                                      placeholder="Write announcement content here..." required></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="dashboard.php" class="btn btn-outline-secondary"
                               style="border-radius:10px;">Cancel</a>
                            <button type="submit" name="btnSave" class="btn btn-danger"
                                    style="background-color:#a3262a; border-radius:10px;">
                                Publish
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
