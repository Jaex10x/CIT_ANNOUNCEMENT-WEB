<?php 
	$connection = new mysqli('localhost', 'root','','dbannouncment');
	
	if (!$connection){
		die (mysqli_error($mysqli));
	}
		
?>