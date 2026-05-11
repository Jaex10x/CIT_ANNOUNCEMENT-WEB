<?php
session_start();
if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'admin') {
    header("Location: login.php");
    exit();
}
include 'connect.php';
require_once 'includes/header.php';