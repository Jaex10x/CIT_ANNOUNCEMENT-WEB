<?php 
	$connection = new mysqli('localhost', 'root','','dbannouncement');
	
	if (!$connection){
		die (mysqli_error($mysqli));
	}
		
?>