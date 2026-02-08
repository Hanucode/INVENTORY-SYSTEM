<?php 
// Database connection file include karein
include 'datawase/config.php'; 

// Step 1: Session start karna (Sabse top par)
session_start();

// Agar user pehle se login hai, toh use sahi page par bhej dein
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_type'] == 'admin') {
        header("Location: admin/dashbord.php");
    } else {
        header("Location: user/userpage.php");
    }
    exit();
}

// --- LOGIN LOGIC ---
if (isset($_POST['login_btn'])) {
    $user_input = mysqli_real_escape_string($conn, $_POST['user_input']);
    $password = $_POST['password'];

    // Database mein Email ya Name se check karein
    $query = "SELECT * FROM user WHERE email='$user_input' OR name='$user_input'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Password verify kar rahe hain
        if (password_verify($password, $row['password'])) {
            
            // Step 2: Session mein data store karna (Email include kar li hai)
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_type'] = $row['user_type'];
            $_SESSION['user_email'] = $row['email']; // <--- Ab ye userdata.php ki warning fix kar dega

            // Role ke mutabiq redirect karein
            if ($row['user_type'] == 'admin') {
                header("Location: admin/dashbord.php");
            } else {
                header("Location: user/userpage.php");
            }
            exit();
        } else {
            $error_msg = "Wrong Password! Dubara check karein.";
        }
    } else {
        $error_msg = "Is Name ya Email se koi account nahi mila!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { background-color: #f8f9fa; }
        .auth-container { height: 100vh; display: flex; align-items: center; justify-content: center; }
        .auth-card { background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .auth-title { font-weight: bold; color: #dc3545; }
        .form-control:focus { border-color: #dc3545; box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25); }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="auth-card text-center">
        <h2 class="auth-title">Login Panel</h2>
        <p class="text-muted">Enter your details to continue</p>

        <?php if(isset($error_msg)): ?>
            <div class="alert alert-danger alert-dismissible fade show text-start" role="alert">
                <small><?php echo $error_msg; ?></small>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="text-start">
            <div class="mb-3">
                <label class="form-label small fw-bold">Username or Email</label>
                <input type="text" name="user_input" class="form-control" placeholder="Enter Username/Email" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label small fw-bold">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" name="login_btn" class="btn btn-danger py-2">Login Now</button>
            </div>
            
            <p class="mt-4 small">
                Don't have an account? <a href="register.php" class="text-danger text-decoration-none fw-bold">Register here</a>
            </p>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>