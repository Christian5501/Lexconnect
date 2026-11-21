<?php
// about.php - About Us Page
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - LexConnect</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<nav>
  <div class="nav-inner">
    <a href="index.php" class="nav-logo">Lex<span>Connect</span></a>
    <div class="nav-links">
      <a href="index.php">Home</a>
      <a href="about.php" class="active nav-link-hide">About Us</a>
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

<!-- PAGE HEADER -->
<section style="background: var(--navy); padding: 4rem 2rem 3rem; text-align: center;">
  <div style="max-width: 640px; margin: 0 auto;">
    <div class="hero-badge">Our Story</div>
    <h1 style="font-family: var(--font-heading); font-size: clamp(2rem, 4vw, 3rem); color: var(--white); margin-top: 1rem;">
      About <em style="color: var(--gold); font-style: normal;">LexConnect</em>
    </h1>
    <p style="color: rgba(255,255,255,0.7); font-size: 1.05rem; margin-top: 1rem;">
      We believe everyone deserves access to quality legal representation — and we built the platform to make that possible.
    </p>
  </div>
</section>

<!-- MISSION -->
<section class="section">
  <div class="container">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center;">
      <div>
        <h2 class="section-title">Our <span>Mission</span></h2>
        <p style="color: var(--gray-600); margin-bottom: 1.25rem;">
          LexConnect was created to solve a simple but critical problem: many people who need legal help do not know how to find a trustworthy lawyer, and the process can feel overwhelming.
        </p>
        <p style="color: var(--gray-600); margin-bottom: 1.25rem;">
          Our platform connects clients directly with qualified advocates through a simple, transparent appointment system. You describe your problem, our secretary matches you with the right lawyer, and your consultation is confirmed — all in one place.
        </p>
        <p style="color: var(--gray-600);">
          We serve clients in all legal sectors: civil cases, criminal defense, family law, business contracts, land disputes, and more.
        </p>
      </div>
      <div>
        <div class="card" style="border-left: 4px solid var(--gold);">
          <h3 style="font-family: var(--font-heading); font-size: 1.4rem; color: var(--navy); margin-bottom: 1rem;">Legal Areas We Cover</h3>
          <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.6rem;">
            <?php
            $areas = [
              "⚖️ Civil & Criminal Court Cases",
              "🏠 Land and Property Disputes",
              "💼 Business & Contract Law",
              "👨‍👩‍👧 Family Law & Divorce",
              "🤝 Labour & Employment Law",
              "📄 Document Review & Drafting",
              "🏢 Company Registration & Compliance",
            ];
            foreach ($areas as $area) {
              echo "<li style='font-size:0.93rem; color:var(--gray-700); display:flex; gap:0.5rem; align-items:center;'>$area</li>";
            }
            ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<hr class="divider-gold" style="max-width:1100px;margin:0 auto;">

<!-- HOW THE SYSTEM WORKS -->
<section class="section">
  <div class="container">
    <h2 class="section-title">How Our <span>System Works</span></h2>
    <p class="section-sub">Our three-role appointment system ensures every client gets the right lawyer for their situation.</p>

    <div class="card-grid">
      <div class="feature-card">
        <div class="feature-icon">👤</div>
        <h3>The Client</h3>
        <p>You register on the platform, fill in your details, and submit an appointment request. You write a short description of your legal problem so the right lawyer can be matched to you.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">📋</div>
        <h3>The Secretary</h3>
        <p>Our secretary receives your request, reviews the case details, and forwards it to the most suitable lawyer on our team. The secretary is your point of contact throughout the process.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">⚖️</div>
        <h3>The Lawyer</h3>
        <p>Once the lawyer receives your forwarded request, they review it and confirm the appointment — setting a date and time that works for your consultation.</p>
      </div>
    </div>
  </div>
</section>

<!-- TEAM -->
<section class="section" style="background: var(--white);">
  <div class="container">
    <h2 class="section-title">Meet the <span>Team</span></h2>
    <p class="section-sub">Dedicated professionals committed to making legal help accessible to all.</p>
    <div class="team-grid">
      <?php
      $team = [
        ["JH", "Adv. Jean Habimana", "Senior Lawyer — Civil & Criminal"],
        ["AM", "Adv. Aline Mukamana", "Lawyer — Family & Land Law"],
        ["SN", "Sarah Nzeyimana",     "Legal Secretary"],
        ["PM", "Patrick Mugisha",     "Lawyer — Business & Contracts"],
      ];
      foreach ($team as $member) {
        echo "
        <div class='team-card'>
          <div class='team-avatar'>{$member[0]}</div>
          <h4>{$member[1]}</h4>
          <span>{$member[2]}</span>
        </div>";
      }
      ?>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <p>&copy; <?php echo date('Y'); ?> <strong>LexConnect</strong>. All rights reserved. &nbsp;|&nbsp; Kigali, Rwanda</p>
</footer>

</body>
</html>
