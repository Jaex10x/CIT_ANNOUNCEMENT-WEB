<?php
session_start();
if (isset($_SESSION['userType'])) {
    if ($_SESSION['userType'] === 'admin') {
        header("Location: dashboard.php");
    } else {
        header("Location: student_dashboard.php");
    }
} else {
    header("Location: login.php");
}
exit();
?>
