<?php
// register.php - Client Registration
session_start();
require 'db.php';

$error   = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = htmlspecialchars(trim($_POST['full_name']));
    $email    = htmlspecialchars(trim($_POST['email']));
    $phone    = htmlspecialchars(trim($_POST['phone']));
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    // Simple validation
    if (empty($name) || empty($email) || empty($password)) {
        $error = "Please fill in all required fields.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "An account with this email already exists.";
        } else {
            // Hash password and save user
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, phone, role) VALUES (?, ?, ?, ?, 'client')");
            $stmt->bind_param("ssss", $name, $email, $hashed, $phone);

            if ($stmt->execute()) {
                $success = "Account created! You can now login.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - LexConnect</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<nav>
  <div class="nav-inner">
    <a href="index.php" class="nav-logo">Lex<span>Connect</span></a>
    <div class="nav-links">
      <a href="index.php">Home</a>
      <a href="login.php">Login</a>
    </div>
  </div>
</nav>

<section class="section">
  <div class="container">
    <div class="form-wrap">
      <h2>Create Account</h2>
      <p class="subtitle">Register as a client to book your legal consultation.</p>

      <?php if ($success): ?>
        <div class="alert alert-success">✅ <?php echo $success; ?> <a href="login.php">Login here</a></div>
      <?php endif; ?>

      <?php if ($error): ?>
        <div class="alert alert-error">⚠️ <?php echo $error; ?></div>
      <?php endif; ?>

      <form method="POST" action="register.php">
        <div class="form-group">
          <label for="full_name">Full Name *</label>
          <input type="text" id="full_name" name="full_name" placeholder="e.g. Jean Pierre Nkurunziza" required>
        </div>

        <div class="form-group">
          <label for="email">Email Address *</label>
          <input type="email" id="email" name="email" placeholder="your@email.com" required>
        </div>

        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input type="text" id="phone" name="phone" placeholder="+250 7XX XXX XXX">
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="password">Password *</label>
            <input type="password" id="password" name="password" placeholder="Min. 6 characters" required>
          </div>
          <div class="form-group">
            <label for="confirm_password">Confirm Password *</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat password" required>
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-full">Create My Account</button>
      </form>

      <p class="form-footer">Already have an account? <a href="login.php">Login here</a></p>
    </div>
  </div>
</section>

<footer>
  <p>&copy; <?php echo date('Y'); ?> <strong>LexConnect</strong>. All rights reserved.</p>
</footer>

</body>
</html>
