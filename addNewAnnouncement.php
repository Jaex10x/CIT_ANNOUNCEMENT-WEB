<?php
    include 'connect.php';
    require_once 'includes/header.php';

    if (isset($_POST['btnSave'])) {
        $title = $_POST['txtTitle'];
        $date  = $_POST['txtDate'];
        $text  = $_POST['txtContent'];

        $sql = "INSERT INTO announcements (title, date_posted, announcement_text) 
                VALUES ('$title', '$date', '$text')";

        if ($connection->query($sql) === TRUE) {
            // This alert and redirect will bring you back to the screen in your image
            echo "<script>
                    alert('Announcement Published Successfully!');
                    window.location.href='dashboard.php';
                  </script>";
        } else {
            echo "Error: " . $connection->error;
        }
    }
?>

<div class="container mt-5">
    <form method="POST">
        <input type="text" name="txtTitle" placeholder="Title" required class="form-control mb-2">
        <input type="text" name="txtDate" placeholder="Date" required class="form-control mb-2">
        <textarea name="txtContent" placeholder="Content" required class="form-control mb-2"></textarea>
        <button type="submit" name="btnSave" class="btn btn-danger">Publish</button>
    </form>
</div>