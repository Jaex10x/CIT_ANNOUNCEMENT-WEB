<?php
session_start();
include 'connect.php';
require_once 'includes/header.php';

$success = '';
$error   = '';

if (isset($_POST['btnRegister'])) {
    $fname     = trim($_POST['txtfirstname']);
    $lname     = trim($_POST['txtlastname']);
    $gender    = $_POST['txtgender'];
    $email     = trim($_POST['txtemail']);
    $student_id = trim($_POST['txtstudent_id']);  
    $program    = trim($_POST['txtprogram']);
    $year_level = trim($_POST['txtyear_level']);
    $password   = $_POST['txtpassword'];
    $confirm    = $_POST['txtconfirmpassword'];

    if ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        $check = $connection->prepare("SELECT id FROM tbuser WHERE student_id = ?");
        $check->bind_param("s", $student_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = 'Student ID already exists. Please use another ID or login.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $role   = 'student';  

            $stmt = $connection->prepare(
                "INSERT INTO tbuser (firstname, lastname, gender, student_id, email, password, role, program, year_level) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("sssssssss", $fname, $lname, $gender, $student_id, $email, $hashed, $role, $program, $year_level);

            if ($stmt->execute()) {
                $success = 'Registration successful! Use your Student ID to login.';
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
                            <label>Email</label>
                            <input type="email" name="txtemail" class="form-control" 
                                   placeholder="e.g., john@cit.edt" required>
                            <small class="text-muted">This will be your email</small>
                        </div>
                        
                       
                        <div class="form-group">
                            <label>ID Number</label>
                            <input type="text" name="txtstudent_id" class="form-control" 
                                   placeholder="e.g., 2024-00001" required>
                            <small class="text-muted">This will be your username/login ID</small>
                        </div>
                        
                        <div class="form-group">
                            <label>Program / Course</label>
                            <select name="txtprogram" class="form-control" required>
                                <option value="">-- Select Program --</option>
                                <option value="BSIT">BS Information Technology</option>
                                <option value="BSCS">BS Computer Science</option>
                                <option value="BSIS">BS Information Systems</option>
                                <option value="BSCE">BS Civil Engineering</option>
                                <option value="BSECE">BS Electronics Engineering</option>
                                <option value="BSME">BS Mechanical Engineering</option>
                                <option value="BSA">BS Accountancy</option>
                                <option value="BSBA">BS Business Administration</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Year Level</label>
                            <select name="txtyear_level" class="form-control" required>
                                <option value="">-- Select Year Level --</option>
                                <option value="1">1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3">3rd Year</option>
                                <option value="4">4th Year</option>
                            </select>
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