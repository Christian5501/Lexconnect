<?php
// index.php - Home Page
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LexConnect - Legal Appointment Platform</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<nav>
  <div class="nav-inner">
    <a href="index.php" class="nav-logo">Lex<span>Connect</span></a>
    <div class="nav-links">
      <a href="index.php" class="active">Home</a>
      <a href="about.php" class="nav-link-hide">About Us</a>
      <a href="contact.php" class="nav-link-hide">Contact</a>
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

<!-- HERO -->
<section class="hero">
  <div class="hero-content">
    <div class="hero-badge">&#9878; Rwanda's Legal Appointment Platform</div>
    <h1>Connect with the Right <em>Lawyer</em>, Right Away</h1>
    <p>LexConnect makes it simple for clients to book legal consultations. Register, describe your case, and let our team connect you with the right advocate.</p>
    <div class="hero-actions">
      <a href="register.php" class="btn btn-primary">Book a Consultation</a>
      <a href="about.php" class="btn btn-outline">Learn More</a>
    </div>
  </div>
</section>

<!-- WHAT WE DO -->
<section class="section">
  <div class="container">
    <h2 class="section-title">What We <span>Do</span></h2>
    <p class="section-sub">LexConnect bridges the gap between clients and qualified lawyers, making legal help accessible to everyone.</p>

    <div class="card-grid">
      <div class="feature-card">
        <div class="feature-icon">&#9878;</div>
        <h3>Legal Consultations</h3>
        <p>We help clients connect with qualified lawyers for court representation, contract reviews, family law, business law, and more.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">&#128197;</div>
        <h3>Easy Appointments</h3>
        <p>Register once, describe your problem, and our secretary team handles the rest — booking you with the right legal professional.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">&#128274;</div>
        <h3>Confidential Process</h3>
        <p>Your case details are kept strictly private. Only your assigned lawyer and our secretary can view your information.</p>
      </div>
    </div>
  </div>
</section>

<hr class="divider-gold" style="max-width:1100px;margin:0 auto;">

<!-- HOW IT WORKS -->
<section class="section">
  <div class="container">
    <h2 class="section-title">How It <span>Works</span></h2>
    <p class="section-sub">From registration to consultation in just a few simple steps.</p>

    <div class="steps">
      <div class="step">
        <div class="step-num">1</div>
        <h4>Register</h4>
        <p>Create your free client account with your name, email, and phone number.</p>
      </div>
      <div class="step">
        <div class="step-num">2</div>
        <h4>Submit Request</h4>
        <p>Describe your legal issue briefly and request an appointment.</p>
      </div>
      <div class="step">
        <div class="step-num">3</div>
        <h4>Secretary Reviews</h4>
        <p>Our secretary reviews your request and forwards it to the right lawyer.</p>
      </div>
      <div class="step">
        <div class="step-num">4</div>
        <h4>Lawyer Confirms</h4>
        <p>Your lawyer reviews the case and confirms a consultation time for you.</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA BANNER -->
<section style="background: var(--navy); padding: 4rem 2rem; text-align: center;">
  <h2 style="font-family: var(--font-heading); font-size: 2.2rem; color: var(--white); margin-bottom: 0.75rem;">
    Ready to Get <em style="color: var(--gold); font-style: normal;">Legal Help?</em>
  </h2>
  <p style="color: rgba(255,255,255,0.7); margin-bottom: 2rem; font-size: 1.05rem;">
    Join hundreds of clients who found the right legal support through LexConnect.
  </p>
  <a href="register.php" class="btn btn-primary">Create Your Account</a>
</section>

<!-- FOOTER -->
<footer>
  <p>&copy; <?php echo date('Y'); ?> <strong>LexConnect</strong>. All rights reserved. &nbsp;|&nbsp; Kigali, Rwanda</p>
</footer>

</body>
</html>
