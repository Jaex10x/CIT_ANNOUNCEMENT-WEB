<?php
session_start();
if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'admin') {
    header("Location: login.php");
    exit();
}
include 'connect.php';
require_once 'includes/header.php';

$success = '';
$error   = '';
$editRow = null;


if (isset($_POST['btnCreate'])) {
    $firstname = trim($_POST['txtFirstname']);
    $lastname  = trim($_POST['txtLastname']);
    $birthdate = $_POST['txtBirthdate'];
    $gender    = $_POST['txtGender'];
    $mobileno  = trim($_POST['txtMobileno']);
    $email     = trim($_POST['txtEmail']);
    $role      = $_POST['txtRole'];
    $password  = $_POST['txtPassword'];
    
    
    $student_id = trim($_POST['txtStudent_id'] ?? '');
    $program    = trim($_POST['txtProgram'] ?? '');
    $year_level = trim($_POST['txtyear_level'] ?? '');
    
    
    $emp_id     = trim($_POST['txtEmp_id'] ?? '');
    $position   = trim($_POST['txtPosition'] ?? '');
    $department = trim($_POST['txtDepartment'] ?? '');

    $check = $connection->prepare("SELECT id FROM tbuser WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = 'Email already exists.';
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $connection->prepare(
            "INSERT INTO tbuser (firstname, lastname, birthdate, gender, mobileno, email, password, role, student_id, program, year_level, emp_id, position, department)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("ssssssssssssss", 
            $firstname, $lastname, $birthdate, $gender, $mobileno, $email, $hashed, $role,
            $student_id, $program, $year_level, $emp_id, $position, $department
        );
        
        if ($stmt->execute()) {
            $success = 'User created successfully.';
            echo "<script>setTimeout(function(){ window.location.href='manage_users.php'; }, 1000);</script>";
        } else {
            $error = 'Error: ' . $connection->error;
        }
    }
}


if (isset($_POST['btnUpdate'])) {
    $id        = (int) $_POST['txtId'];
    $firstname = trim($_POST['txtFirstname']);
    $lastname  = trim($_POST['txtLastname']);
    $birthdate = $_POST['txtBirthdate'];
    $gender    = $_POST['txtGender'];
    $mobileno  = trim($_POST['txtMobileno']);
    $email     = trim($_POST['txtEmail']);
    $role      = $_POST['txtRole'];
    
    
    $student_id = trim($_POST['txtStudent_id'] ?? '');
    $program    = trim($_POST['txtProgram'] ?? '');
    $year_level = trim($_POST['txtyear_level'] ?? '');
    
    $emp_id     = trim($_POST['txtEmp_id'] ?? '');
    $position   = trim($_POST['txtPosition'] ?? '');
    $department = trim($_POST['txtDepartment'] ?? '');

    if (!empty($_POST['txtPassword'])) {
        $hashed = password_hash($_POST['txtPassword'], PASSWORD_DEFAULT);
        $stmt = $connection->prepare(
            "UPDATE tbuser SET firstname=?, lastname=?, birthdate=?, gender=?, mobileno=?, email=?, password=?, role=?, 
             student_id=?, program=?, year_level=?, emp_id=?, position=?, department=? WHERE id=?"
        );
        $stmt->bind_param("ssssssssssssssi", 
            $firstname, $lastname, $birthdate, $gender, $mobileno, $email, $hashed, $role,
            $student_id, $program, $year_level, $emp_id, $position, $department, $id
        );
    } else {
        $stmt = $connection->prepare(
            "UPDATE tbuser SET firstname=?, lastname=?, birthdate=?, gender=?, mobileno=?, email=?, role=?, 
             student_id=?, program=?, year_level=?, emp_id=?, position=?, department=? WHERE id=?"
        );
        $stmt->bind_param("sssssssssssssi", 
            $firstname, $lastname, $birthdate, $gender, $mobileno, $email, $role,
            $student_id, $program, $year_level, $emp_id, $position, $department, $id
        );
    }

    if ($stmt->execute()) {
        $success = 'User updated successfully.';
        echo "<script>setTimeout(function(){ window.location.href='manage_users.php'; }, 1000);</script>";
    } else {
        $error = 'Error: ' . $connection->error;
    }
}


if (isset($_GET['delete'])) {
    $id   = (int) $_GET['delete'];
    $stmt = $connection->prepare("DELETE FROM tbuser WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $success = 'User deleted.';
    } else {
        $error = 'Error deleting user.';
    }
}


if (isset($_GET['edit'])) {
    $id   = (int) $_GET['edit'];
    $stmt = $connection->prepare("SELECT * FROM tbuser WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $editRow = $stmt->get_result()->fetch_assoc();
}


$users = $connection->query("SELECT * FROM tbuser ORDER BY id DESC");
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="color:#800000; font-weight:bold;">Manage Users</h2>
        <a href="dashboard.php" class="btn btn-outline-danger rounded-pill px-4"
           style="border-color:#a3262a; color:#a3262a;">← Back to Dashboard</a>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card shadow border-0 mb-5" style="border-radius:16px;">
        <div class="card-header text-white"
             style="background-color:#a3262a; border-radius:16px 16px 0 0;">
            <h5 class="mb-0"><?= $editRow ? 'Edit User' : 'Add New User' ?></h5>
        </div>
        <div class="card-body p-4">
            <form method="POST">
                <?php if ($editRow): ?>
                    <input type="hidden" name="txtId" value="<?= (int)$editRow['id'] ?>">
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold">First Name</label>
                        <input type="text" name="txtFirstname" class="form-control"
                               value="<?= htmlspecialchars($editRow['firstname'] ?? '') ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold">Last Name</label>
                        <input type="text" name="txtLastname" class="form-control"
                               value="<?= htmlspecialchars($editRow['lastname'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label class="font-weight-bold">Birth Date</label>
                        <input type="date" name="txtBirthdate" class="form-control"
                               value="<?= htmlspecialchars($editRow['birthdate'] ?? '') ?>" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="font-weight-bold">Gender</label>
                        <select name="txtGender" class="form-control" required>
                            <option value="">-- Select --</option>
                            <option value="M" <?= ($editRow['gender'] ?? '') == 'M' ? 'selected' : '' ?>>Male</option>
                            <option value="F" <?= ($editRow['gender'] ?? '') == 'F' ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="font-weight-bold">Mobile No.</label>
                        <input type="text" name="txtMobileno" class="form-control"
                               value="<?= htmlspecialchars($editRow['mobileno'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold">Email</label>
                        <input type="email" name="txtEmail" class="form-control"
                               value="<?= htmlspecialchars($editRow['email'] ?? '') ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold">Role</label>
                        <select name="txtRole" class="form-control" id="roleSelect" required onchange="toggleFields()">
                            <option value="student" <?= ($editRow['role'] ?? '') == 'student' ? 'selected' : '' ?>>Student</option>
                            <option value="admin" <?= ($editRow['role'] ?? '') == 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>
                </div>

            
                <div id="studentFields" style="display: <?= ($editRow['role'] ?? 'student') == 'student' ? 'block' : 'none' ?>;">
                    <div class="card bg-light mb-3">
                        <div class="card-header">Student Information</div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Student ID</label>
                                    <input type="text" name="txtStudent_id" class="form-control"
                                           value="<?= htmlspecialchars($editRow['student_id'] ?? '') ?>"
                                           placeholder="e.g., 2024-00001">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Program/Course</label>
                                    <select name="txtProgram" class="form-control">
                                        <option value="">-- Select --</option>
                                        <option value="BSIT" <?= ($editRow['program'] ?? '') == 'BSIT' ? 'selected' : '' ?>>BS Information Technology</option>
                                        <option value="BSCS" <?= ($editRow['program'] ?? '') == 'BSCS' ? 'selected' : '' ?>>BS Computer Science</option>
                                        <option value="BSIS" <?= ($editRow['program'] ?? '') == 'BSIS' ? 'selected' : '' ?>>BS Information Systems</option>
                                        <option value="BSCE" <?= ($editRow['program'] ?? '') == 'BSCE' ? 'selected' : '' ?>>BS Civil Engineering</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Year Level</label>
                                    <select name="txtyear_level" class="form-control">
                                        <option value="">-- Select --</option>
                                        <option value="1" <?= ($editRow['year_level'] ?? '') == '1' ? 'selected' : '' ?>>1st Year</option>
                                        <option value="2" <?= ($editRow['year_level'] ?? '') == '2' ? 'selected' : '' ?>>2nd Year</option>
                                        <option value="3" <?= ($editRow['year_level'] ?? '') == '3' ? 'selected' : '' ?>>3rd Year</option>
                                        <option value="4" <?= ($editRow['year_level'] ?? '') == '4' ? 'selected' : '' ?>>4th Year</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="adminFields" style="display: <?= ($editRow['role'] ?? '') == 'admin' ? 'block' : 'none' ?>;">
                    <div class="card bg-light mb-3">
                        <div class="card-header">Admin Information</div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Employee ID</label>
                                    <input type="text" name="txtEmp_id" class="form-control"
                                           value="<?= htmlspecialchars($editRow['emp_id'] ?? '') ?>"
                                           placeholder="e.g., EMP-001">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Position</label>
                                    <input type="text" name="txtPosition" class="form-control"
                                           value="<?= htmlspecialchars($editRow['position'] ?? '') ?>"
                                           placeholder="e.g., Department Head">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Department</label>
                                    <select name="txtDepartment" class="form-control">
                                        <option value="">-- Select --</option>
                                        <option value="IT" <?= ($editRow['department'] ?? '') == 'IT' ? 'selected' : '' ?>>Information Technology</option>
                                        <option value="CS" <?= ($editRow['department'] ?? '') == 'CS' ? 'selected' : '' ?>>Computer Science</option>
                                        <option value="ENG" <?= ($editRow['department'] ?? '') == 'ENG' ? 'selected' : '' ?>>Engineering</option>
                                        <option value="REG" <?= ($editRow['department'] ?? '') == 'REG' ? 'selected' : '' ?>>Registrar's Office</option>
                                        <option value="OSA" <?= ($editRow['department'] ?? '') == 'OSA' ? 'selected' : '' ?>>Student Affairs</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">
                        Password
                        <?= $editRow ? '<small class="text-muted">(leave blank to keep current)</small>' : '' ?>
                    </label>
                    <input type="password" name="txtPassword" class="form-control"
                           <?= $editRow ? '' : 'required' ?>
                           placeholder="<?= $editRow ? 'Leave blank to keep current' : 'Enter password' ?>">
                </div>

                <?php if ($editRow): ?>
                    <button type="submit" name="btnUpdate" class="btn btn-danger"
                            style="background-color:#a3262a; border-radius:10px;">Save Changes</button>
                    <a href="manage_users.php" class="btn btn-outline-secondary ml-2"
                       style="border-radius:10px;">Cancel</a>
                <?php else: ?>
                    <button type="submit" name="btnCreate" class="btn btn-danger"
                            style="background-color:#a3262a; border-radius:10px;">Add User</button>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="card shadow border-0" style="border-radius:16px;">
        <div class="card-header text-white"
             style="background-color:#a3262a; border-radius:16px 16px 0 0;">
            <h5 class="mb-0">All Users</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead style="background-color:#f8f8f8;">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email/Student ID</th>
                        <th>Role</th>
                        <th>Student ID</th>
                        <th>Program</th>
                        <th>Year Level</th>
                        <th>Emp ID</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($users->num_rows == 0): ?>
                        <tr><td colspan="11" class="text-center text-muted py-4">No users found.</td></tr>
                    <?php endif; ?>
                    <?php while ($u = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?= $u['id'] ?></td>
                            <td><?= htmlspecialchars($u['firstname'] . ' ' . $u['lastname']) ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td>
                                <span class="badge badge-<?= $u['role'] == 'admin' ? 'danger' : 'info' ?>">
                                    <?= ucfirst($u['role']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($u['student_id'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($u['program'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($u['year_level'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($u['emp_id'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($u['position'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($u['department'] ?? '-') ?></td>
                            <td>
                                <a href="manage_users.php?edit=<?= $u['id'] ?>"
                                   class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                   style="border-color:#a3262a; color:#a3262a;">Edit</a>
                                <a href="manage_users.php?delete=<?= $u['id'] ?>"
                                   class="btn btn-sm btn-outline-secondary rounded-pill px-3 ml-1"
                                   onclick="return confirm('Delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function toggleFields() {
    var role = document.getElementById('roleSelect').value;
    var studentFields = document.getElementById('studentFields');
    var adminFields = document.getElementById('adminFields');
    
    if (role === 'student') {
        studentFields.style.display = 'block';
        adminFields.style.display = 'none';
    } else if (role === 'admin') {
        studentFields.style.display = 'none';
        adminFields.style.display = 'block';
    }
}
</script>


<?php require_once 'includes/footer.php'; ?>