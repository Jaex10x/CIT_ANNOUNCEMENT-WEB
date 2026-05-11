<?php
session_start();
if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'admin') {
    header("Location: login.php");
    exit();
}
include 'connect.php';
require_once 'includes/header.php';

$row = [];

if (isset($_GET['id'])) {
    $id   = (int) $_GET['id'];
    $stmt = $connection->prepare("SELECT * FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row    = $result->fetch_assoc();

    if (!$row) {
        echo "<script>alert('Record not found.'); window.location='dashboard.php';</script>";
        exit();
    }
}

if (isset($_POST['btnUpdate'])) {
    $id    = (int) $_POST['txtId'];
    $title = trim($_POST['txtTitle']);
    $date  = $_POST['txtDate'];
    $text  = trim($_POST['txtContent']);

    $stmt = $connection->prepare(
        "UPDATE announcements SET title = ?, date_posted = ?, announcement_text = ? WHERE id = ?"
    );
    $stmt->bind_param("sssi", $title, $date, $text, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Updated Successfully!'); window.location='dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating record.');</script>";
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0" style="border-radius: 20px;">
                <div class="card-header text-white text-center"
                     style="background-color:#a3262a; border-radius:20px 20px 0 0;">
                    <h4 class="mb-0">Edit Announcement</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        <input type="hidden" name="txtId" value="<?= (int)$row['id'] ?>">

                        <div class="form-group">
                            <label class="font-weight-bold">Title</label>
                            <input type="text" name="txtTitle" class="form-control"
                                   value="<?= htmlspecialchars($row['title']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Date</label>
                            <input type="date" name="txtDate" class="form-control"
                                   value="<?= htmlspecialchars($row['date_posted']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Content</label>
                            <textarea name="txtContent" class="form-control" rows="4" required>
<?= htmlspecialchars($row['announcement_text']) ?></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="btnUpdate" class="btn btn-danger"
                                    style="background-color:#a3262a; border-radius:15px;">
                                Save Changes
                            </button>
                            <a href="dashboard.php" class="btn btn-outline-secondary mt-2"
                               style="border-radius:15px;">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
