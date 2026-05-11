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

    $check = $connection->prepare("SELECT id FROM tbuser WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = 'Email already exists.';
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt   = $connection->prepare(
            "INSERT INTO tbuser (firstname, lastname, birthdate, gender, mobileno, email, password, role)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("ssssssss", $firstname, $lastname, $birthdate, $gender, $mobileno, $email, $hashed, $role);
        if ($stmt->execute()) {
            $success = 'User created successfully.';
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

    if (!empty($_POST['txtPassword'])) {
        $hashed = password_hash($_POST['txtPassword'], PASSWORD_DEFAULT);
        $stmt   = $connection->prepare(
            "UPDATE tbuser SET firstname=?, lastname=?, birthdate=?, gender=?, mobileno=?, email=?, password=?, role=? WHERE id=?"
        );
        $stmt->bind_param("ssssssssi", $firstname, $lastname, $birthdate, $gender, $mobileno, $email, $hashed, $role, $id);
    } else {
        $stmt = $connection->prepare(
            "UPDATE tbuser SET firstname=?, lastname=?, birthdate=?, gender=?, mobileno=?, email=?, role=? WHERE id=?"
        );
        $stmt->bind_param("sssssssi", $firstname, $lastname, $birthdate, $gender, $mobileno, $email, $role, $id);
    }

    if ($stmt->execute()) {
        $success = 'User updated successfully.';
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

    <!-- FORM -->
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
                        <select name="txtRole" class="form-control" required>
                            <option value="admin"   <?= ($editRow['role'] ?? '') == 'admin'   ? 'selected' : '' ?>>Admin</option>
                            <option value="student" <?= ($editRow['role'] ?? '') == 'student' ? 'selected' : '' ?>>Student</option>
                        </select>
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

    <!-- TABLE -->
    <div class="card shadow border-0" style="border-radius:16px;">
        <div class="card-header text-white"
             style="background-color:#a3262a; border-radius:16px 16px 0 0;">
            <h5 class="mb-0">All Users</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead style="background-color:#f8f8f8;">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Birthdate</th>
                        <th>Gender</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($users->num_rows == 0): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">No users found.</td></tr>
                    <?php endif; ?>
                    <?php while ($u = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?= $u['id'] ?></td>
                            <td><?= htmlspecialchars($u['firstname'] . ' ' . $u['lastname']) ?></td>
                            <td><?= htmlspecialchars($u['birthdate']) ?></td>
                            <td><?= htmlspecialchars($u['gender']) ?></td>
                            <td><?= htmlspecialchars($u['mobileno']) ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td>
                                <span class="badge badge-<?= $u['role'] == 'admin' ? 'danger' : ($u['role'] == 'faculty' ? 'warning' : 'info') ?>">
                                    <?= ucfirst($u['role']) ?>
                                </span>
                            </td>
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

<div class="text-center mt-4 py-3" style="border-top:1px solid #ddd; color:#666;">
    <small>LVB Copyright 2026</small>
</div>

<?php require_once 'includes/footer.php'; ?>