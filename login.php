<?php
    session_start();
    include 'connect.php';
    require_once 'includes/header.php'; 
?>

<div class="container mt-5">
    <h2 class="text-center" style="color: #800000;">Login</h2>
    <form method="post" class="text-center">
        Email: <input type="text" name="txtemail"><br><br>
        Password: <input type="password" name="txtpassword"><br><br>
        <input type="submit" name="btnLogin" value="Login" class="btn btn-danger"> 
    </form>
</div>

<?php
if(isset($_POST['btnLogin'])){
    $email = $_POST['txtemail'];
    $password = $_POST['txtpassword'];

    $hashed_pword = password_hash($password, PASSWORD_DEFAULT);	

    //paras email og login esa admin
    $sql = "SELECT * FROM tbuser WHERE email = '$email' AND role = 'admin'";

    $result = mysqli_query($connection, $sql);	
		
	$count = mysqli_num_rows($result);
	$row = mysqli_fetch_array($result);

    if($count== 0){
		echo "<script language='javascript'>
				alert('username not existing.');
			</script>";
				  
	//}else if($row[3] != $pwd) {		
	}else if(!password_verify($password,$hashed_pword)){
		echo "<script language='javascript'>
				alert('Incorrect password');s
			</script>";
	}else {		
		$_SESSION['username']=$row[0];
		header("location: dashboard.php");
	}

}

?>