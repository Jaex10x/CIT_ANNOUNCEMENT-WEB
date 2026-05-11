<?php
$connection = new mysqli('localhost', 'root', '', 'dbannouncement');
if ($connection->connect_error) {
    die('Connection failed: ' . $connection->connect_error);
}
?>
