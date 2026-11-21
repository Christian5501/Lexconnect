<?php
// login.php - Login Page (all roles)
session_start();
require 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please enter your email and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, full_name, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // Store session
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role']      = $user['role'];

                // Redirect based on role
                if ($user['role'] == 'client') {
                    header("Location: client_dashboard.php");
                } elseif ($user['role'] == 'secretary') {
                    header("Location: secretary_dashboard.php");
                } elseif ($user['role'] == 'lawyer') {
                    header("Location: lawyer_dashboard.php");
                }
                exit();
            } else {
                $error = "Incorrect password. Please try again.";
            }
        } else {
            $error = "No account found with that email.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - LexConnect</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<nav>
  <div class="nav-inner">
    <a href="index.php" class="nav-logo">Lex<span>Connect</span></a>
    <div class="nav-links">
      <a href="index.php">Home</a>
      <a href="register.php" class="btn-nav">Register</a>
    </div>
  </div>
</nav>

<section class="section">
  <div class="container">
    <div class="form-wrap">
      <h2>Welcome Back</h2>
      <p class="subtitle">Login to access your LexConnect dashboard.</p>

      <?php if ($error): ?>
        <div class="alert alert-error">⚠️ <?php echo $error; ?></div>
      <?php endif; ?>

      <form method="POST" action="login.php">
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" placeholder="your@email.com" required>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Your password" required>
        </div>

        <button type="submit" class="btn btn-primary btn-full">Login</button>
      </form>

      <p class="form-footer">Don't have an account? <a href="register.php">Register here</a></p>

      <hr style="margin: 1.5rem 0; border-color: var(--gray-200);">
      <p style="font-size:0.82rem; color:var(--gray-400); text-align:center;">
        <strong>Demo accounts</strong> (password: <code>password</code>)<br>
        client@lexconnect.rw &nbsp;|&nbsp; secretary@lexconnect.rw &nbsp;|&nbsp; lawyer@lexconnect.rw
      </p>
    </div>
  </div>
</section>

<footer>
  <p>&copy; <?php echo date('Y'); ?> <strong>LexConnect</strong>. All rights reserved.</p>
</footer>

</body>
</html>
