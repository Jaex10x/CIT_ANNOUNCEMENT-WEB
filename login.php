<?php
session_start();
include 'connect.php';

if(isset($_POST['btnLogin'])){
    $email = $_POST['txtemail'];
    $password = $_POST['txtpassword'];
    $userType = $_POST['userType']; 

    $hashed_pword = password_hash($password, PASSWORD_DEFAULT);	

    // Change SQL query based on user type
    if($userType == 'admin'){
        $sql = "SELECT * FROM tbuser WHERE email = '$email' AND role = 'admin'";
    } else {
        $sql = "SELECT * FROM tbstudents WHERE email = '$email'"; 
    }

    $result = mysqli_query($connection, $sql);	
		
    $count = mysqli_num_rows($result);
    $row = mysqli_fetch_array($result);

    if($count == 0){
        echo "<script language='javascript'>
                alert('Username not existing.');
                window.location.href = 'login.php';
              </script>";
        exit();
    } else if(!password_verify($password, $hashed_pword)){
        echo "<script language='javascript'>
                alert('Incorrect password');
                window.location.href = 'login.php';
              </script>";
        exit();
    } else {		
        $_SESSION['username'] = $row[0];
        $_SESSION['userType'] = $userType;
        
        if($userType == 'admin'){
            header("location: dashboard.php");
        } else {
            header("location: student_dashboard.php");
        }
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;600&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <title>CIT-U Login</title>
    <style>
        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
        }

        body {
            min-height: 100vh;
            font-family: 'Lato', sans-serif;
            background: #f0c840;
            background-image:
                radial-gradient(ellipse 80% 60% at 80% 20%, #f5d060 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 10% 80%, #e8b820 0%, transparent 60%);
            display: flex;
            flex-direction: column;
        }

        .top-bar {
            background: #fff;
            border-bottom: 3px solid #800000;
            padding: 10px 24px;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .top-bar img {
            height: 42px;
            width: auto;
        }
        .top-bar .brand {
            font-family: 'EB Garamond', serif;
            font-size: 1rem;
            color: #800000;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            line-height: 1.2;
        }
        .top-bar .brand span {
            display: block;
            font-weight: 400;
            font-size: 0.78rem;
            letter-spacing: 0.25em;
        }

        .main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 8%;
            position: relative;
        }

        .seal-wrap {
            position: absolute;
            left: 5%;
            top: 50%;
            transform: translateY(-50%);
            width: min(420px, 45vw);
            opacity: 0.95;
            pointer-events: none;
            animation: floatSeal 6s ease-in-out infinite;
        }
        .seal-wrap img {
            width: 100%;
            height: auto;
            filter: drop-shadow(0 8px 32px rgba(128,0,0,0.18));
        }

        @keyframes floatSeal {
            0%, 100% { transform: translateY(-50%) translateY(0); }
            50%       { transform: translateY(-50%) translateY(-10px); }
        }

        .auth-card {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(12px);
            border-radius: 18px;
            padding: 40px 36px 36px;
            width: 380px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.18);
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .cit-logo {
            margin-bottom: 20px;
        }
        .cit-logo img {
            width: 80px;
            height: auto;
        }
        .cit-logo h2 {
            font-family: 'EB Garamond', serif;
            font-weight: 700;
            color: #800000;
            font-size: 1.3rem;
            margin-top: 10px;
            margin-bottom: 5px;
        }
        .cit-logo p {
            font-size: 0.7rem;
            color: #666;
            letter-spacing: 0.15em;
        }
        .cit-logo .est {
            font-size: 0.65rem;
            color: #999;
        }

        .user-type {
            margin: 20px 0;
        }
        .user-type label {
            margin: 0 15px;
            cursor: pointer;
        }
        .user-type input {
            margin-right: 5px;
        }

        .auth-card input[type="text"],
        .auth-card input[type="password"],
        .auth-card input[type="email"] {
            width: 100%;
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 0.95rem;
            color: #333;
            margin-bottom: 14px;
            outline: none;
        }
        .auth-card input:focus {
            border-color: #800000;
            box-shadow: 0 0 0 3px rgba(128,0,0,0.1);
        }

        .btn-login {
            background: #800000;
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }
        .btn-login:hover {
            background: #a00000;
        }
        
        .btn-row {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        
        .btn-clear {
            background: #6c757d;
            border: none;
            border-radius: 8px;
            padding: 8px 18px;
            font-size: 0.85rem;
            color: white;
            cursor: pointer;
            flex: 1;
            margin-right: 10px;
        }
        .btn-clear:hover {
            background: #5a6268;
        }
        
        .divider {
            border: none;
            border-top: 1px solid #e0e0e0;
            margin: 20px 0;
        }
    </style>
</head>
<body>

    <div class="top-bar">
        <img src="images/citlogo.png" alt="CIT Logo">
        <div class="brand">
            Cebu Institute of Technology
            <span>University</span>
        </div>
    </div>

    <div class="main">
        <div class="seal-wrap">
            <img src="images/citlogo.png" alt="CIT Seal">
        </div>

        <div class="auth-card">
            <div class="cit-logo">
                <img src="images/citlogo.png" alt="CIT Logo">
                <h2>CEBU INSTITUTE OF TECHNOLOGY</h2>
                <p>UNIVERSITY</p>
                <div class="est">OF TECHNOLOGICAL UNIVERSITY</div>
                <div class="est">1946</div>
            </div>
            
            <h5>User Authentication</h5>
            <hr class="divider">
            
            <form method="post">
                <div class="user-type">
                    <label>
                        <input type="radio" name="userType" value="student" required> Student
                    </label>
                    <label>
                        <input type="radio" name="userType" value="admin" required> Admin
                    </label>
                    <label>
                        <input type="radio" name="userType" value="faculty" required> Faculty
                    </label>
                </div>
                
                <input type="text" name="txtemail" placeholder="Email" autocomplete="email" required>
                <input type="password" name="txtpassword" placeholder="Password" autocomplete="current-password" required>
                
                <div class="btn-row">
                    <button type="reset" class="btn-clear">Clear Entries</button>
                    <button type="submit" name="btnLogin" class="btn-login">Login</button>
                </div>
            </form>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>