<?php
    include 'connect.php';

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $sql = "DELETE FROM announcements WHERE id = $id";

        if ($connection->query($sql) === TRUE) {
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error deleting record: " . $connection->error;
        }
    } else {
        header("Location: dashboard.php");
        exit();
    }
?>