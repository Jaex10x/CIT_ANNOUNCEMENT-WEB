<?php
    include 'connect.php';
    require_once 'includes/header.php';

    // 1. GET THE EXISTING DATA
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $get_record = $connection->query("SELECT * FROM announcements WHERE id = $id");
        $row = $get_record->fetch_assoc();
    }

    // 2. SAVE THE UPDATED DATA
    if (isset($_POST['btnUpdate'])) {
        $id = $_POST['txtId'];
        $title = $_POST['txtTitle'];
        $date = $_POST['txtDate'];
        $text = $_POST['txtContent'];

        $sql = "UPDATE announcements SET 
                title = '$title', 
                date_posted = '$date', 
                announcement_text = '$text' 
                WHERE id = $id";

        if ($connection->query($sql) === TRUE) {
            echo "<script>alert('Updated Successfully!'); window.location='dashboard.php';</script>";
        } else {
            echo "Error: " . $connection->error;
        }
    }
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0" style="border-radius: 20px;">
                <div class="card-header text-white text-center" style="background-color: #a3262a; border-radius: 20px 20px 0 0;">
                    <h4 class="mb-0">Edit Announcement</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        <input type="hidden" name="txtId" value="<?php echo $row['id']; ?>">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Title</label>
                            <input type="text" name="txtTitle" class="form-control" value="<?php echo $row['title']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Date</label>
                            <input type="text" name="txtDate" class="form-control" value="<?php echo $row['date_posted']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Content</label>
                            <textarea name="txtContent" class="form-control" rows="4" required><?php echo $row['announcement_text']; ?></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="btnUpdate" class="btn btn-danger" style="background-color: #a3262a; border-radius: 15px;">
                                Save Changes
                            </button>
                            <a href="dashboard.php" class="btn btn-outline-secondary" style="border-radius: 15px;">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>