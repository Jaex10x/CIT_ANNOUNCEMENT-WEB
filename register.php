<?php
session_start();
include 'connect.php';
require_once 'includes/header.php';

$success = '';
$error   = '';

if (isset($_POST['btnRegister'])) {
    $fname    = trim($_POST['txtfirstname']);
    $lname    = trim($_POST['txtlastname']);
    $gender   = $_POST['txtgender'];
    $email    = trim($_POST['txtemail']);
    $password = $_POST['txtpassword'];
    $confirm  = $_POST['txtconfirmpassword'];
    
    $student_id = trim($_POST['txtstudent_id'] ?? '');  
    $course     = trim($_POST['txtcourse'] ?? '');      

    if ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $check = $connection->prepare("SELECT id FROM tbuser WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = 'Email already exists. Please use another email or login.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $role   = 'student';  

            $stmt = $connection->prepare(
                "INSERT INTO tbuser (firstname, lastname, gender, email, password, role) 
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("ssssss", $fname, $lname, $gender, $email, $hashed, $role);

            if ($stmt->execute()) {
                $success = 'Registration successful! You can now log in as a student.';
                echo "<script>setTimeout(function(){ window.location.href='login.php'; }, 2000);</script>";
            } else {
                $error = 'Error: ' . $connection->error;
            }
        }
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0" style="border-radius: 20px;">
                <div class="card-header text-white text-center"
                     style="background-color: #a3262a; border-radius: 20px 20px 0 0;">
                    <h4 class="mb-0">📚 Student Registration</h4>
                    <small>Create your student account</small>
                </div>
                <div class="card-body p-4">

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="txtfirstname" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="txtlastname" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="txtgender" class="form-control" required>
                                <option value="">-- Select --</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="txtemail" class="form-control" required>
                            <small class="text-muted">Use your valid email address</small>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="txtpassword" class="form-control" required>
                            <small class="text-muted">Minimum 6 characters</small>
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" name="txtconfirmpassword" class="form-control" required>
                        </div>
                        
                        <button type="submit" name="btnRegister"
                                class="btn btn-danger btn-block"
                                style="background-color:#a3262a; border-radius:10px;">
                            Register as Student
                        </button>
                        
                        <hr>
                        
                        <div class="text-center">
                            <p class="mb-0">Already have an account?</p>
                            <a href="login.php" class="btn btn-outline-secondary btn-block mt-2"
                               style="border-radius:10px;">
                                Login Here
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>