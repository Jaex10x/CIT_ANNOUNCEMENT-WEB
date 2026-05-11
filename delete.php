<?php
session_start();
if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'admin') {
    header("Location: login.php");
    exit();
}
include 'connect.php';

if (isset($_GET['id'])) {
    $id   = (int) $_GET['id'];
    $stmt = $connection->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error deleting record: " . htmlspecialchars($connection->error);
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>
