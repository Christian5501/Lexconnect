<?php
// contact.php - Contact Page
session_start();
$sent = false;
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = htmlspecialchars(trim($_POST['name']));
    $email   = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (empty($name) || empty($email) || empty($message)) {
        $error = "Please fill in all fields.";
    } else {
        // In a real project you would send an email here using mail() or a library
        $sent = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - LexConnect</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<nav>
  <div class="nav-inner">
    <a href="index.php" class="nav-logo">Lex<span>Connect</span></a>
    <div class="nav-links">
      <a href="index.php">Home</a>
      <a href="about.php" class="nav-link-hide">About Us</a>
      <a href="contact.php" class="active nav-link-hide">Contact</a>
      <?php if (isset($_SESSION['user_id'])): ?>
        <?php
          $dash = 'client_dashboard.php';
          if ($_SESSION['role'] == 'secretary') $dash = 'secretary_dashboard.php';
          if ($_SESSION['role'] == 'lawyer')    $dash = 'lawyer_dashboard.php';
        ?>
        <a href="<?php echo $dash; ?>" class="btn-nav">Dashboard</a>
      <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php" class="btn-nav">Get Started</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<!-- PAGE HEADER -->
<section style="background: var(--navy); padding: 4rem 2rem 3rem; text-align: center;">
  <div class="hero-badge">Get In Touch</div>
  <h1 style="font-family: var(--font-heading); font-size: clamp(2rem,4vw,3rem); color: var(--white); margin-top: 1rem;">
    Contact <em style="color:var(--gold);font-style:normal;">LexConnect</em>
  </h1>
  <p style="color: rgba(255,255,255,0.7); margin-top: 0.75rem;">We are here to help. Reach out to us any time.</p>
</section>

<!-- CONTACT SECTION -->
<section class="section">
  <div class="container">
    <div class="contact-grid">

      <!-- Contact Info -->
      <div class="contact-info">
        <h2>Our <span style="color:var(--gold)">Office</span></h2>
        <p>Visit us, call us, or send us an email. Our team is available Monday to Friday, 8am – 5pm.</p>

        <div class="contact-item">
          <div class="contact-icon">📍</div>
          <div>
            <h4>Office Address</h4>
            <p>KG 7 Ave, Kacyiru<br>Kigali, Rwanda</p>
          </div>
        </div>

        <div class="contact-item">
          <div class="contact-icon">📞</div>
          <div>
            <h4>Phone Numbers</h4>
            <p>+250 788 000 100<br>+250 722 000 200</p>
          </div>
        </div>

        <div class="contact-item">
          <div class="contact-icon">✉️</div>
          <div>
            <h4>Email Address</h4>
            <p>info@lexconnect.rw<br>support@lexconnect.rw</p>
          </div>
        </div>

        <div class="contact-item">
          <div class="contact-icon">🕐</div>
          <div>
            <h4>Working Hours</h4>
            <p>Monday – Friday: 8:00 AM – 5:00 PM<br>Saturday: 9:00 AM – 12:00 PM</p>
          </div>
        </div>
      </div>

      <!-- Contact Form -->
      <div>
        <div class="form-wrap" style="max-width:100%;">
          <h2>Send a Message</h2>
          <p class="subtitle">Have a question? Write to us and we will get back to you shortly.</p>

          <?php if ($sent): ?>
            <div class="alert alert-success">✅ Your message has been sent! We will get back to you soon.</div>
          <?php endif; ?>

          <?php if ($error): ?>
            <div class="alert alert-error">⚠️ <?php echo $error; ?></div>
          <?php endif; ?>

          <form method="POST" action="contact.php">
            <div class="form-group">
              <label for="name">Full Name</label>
              <input type="text" id="name" name="name" placeholder="Your full name" required>
            </div>
            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" id="email" name="email" placeholder="your@email.com" required>
            </div>
            <div class="form-group">
              <label for="message">Message</label>
              <textarea id="message" name="message" placeholder="Write your message here..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-full">Send Message</button>
          </form>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <p>&copy; <?php echo date('Y'); ?> <strong>LexConnect</strong>. All rights reserved. &nbsp;|&nbsp; Kigali, Rwanda</p>
</footer>

</body>
</html>
