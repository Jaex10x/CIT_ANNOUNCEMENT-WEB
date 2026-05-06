<?php
    include 'connect.php';
    
    if (!$connection) {
        die('Could not connect: ' . mysqli_connect_error());
    }
    
    $query = 'SELECT * from tbstudents'; 
    $resultset = mysqli_query($connection, $query);
    
    /* 
       If you decide to use the BSIT counter later, 
       make sure to change tblstudent there as well.
    */
    //$querybsit = 'SELECT count(*) as total from tbstudents where program = "BSIT"';
    //$resultset1 = mysqli_query($connection, $querybsit);
    //$count = mysqli_fetch_assoc($resultset1); 
?>