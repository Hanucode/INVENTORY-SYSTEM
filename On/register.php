<?php 
include 'datawase/config.php'; 
session_start(); // Alerts ke liye session start kiya

if (isset($_POST['register_btn'])) {
    $name = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); // Security ke liye hashing
    $type = $_POST['user_type'];

    // Check if email already exists
    $check_email = mysqli_query($conn, "SELECT email FROM user WHERE email='$email'");
    
    if (mysqli_num_rows($check_email) > 0) {
        $error_msg = "Email already exists!";
    } else {
        $query = "INSERT INTO user (name, email, password, user_type) VALUES ('$name', '$email', '$pass', '$type')";
        if (mysqli_query($conn, $query)) {
            $success_msg = "Account created successfully! <a href='login.php'>Login here</a>";
        } else {
            $error_msg = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-card text-center">
        <h1 class="auth-title">Register</h1>
        <p class="system-subtitle">Create New Account</p>

        <?php if(isset($error_msg)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error_msg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if(isset($success_msg)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success_msg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST" class="text-start" id="regForm">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="fullname" class="form-control" placeholder="Your Name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Create Password" required>
            </div>

            <input type="hidden" name="user_type" value="user">

            <button type="submit" name="register_btn" class="btn btn-danger btn-register w-100 mt-2">Register</button>
            
            <p class="login-link text-center">
                Already have an account? <a href="login.php" class="text-primary text-decoration-none">Login</a>
            </p>
        </form>
    </div>
</div>

<script src="js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>