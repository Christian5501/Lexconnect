<?php
// lawyer_dashboard.php - Lawyer Dashboard
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'lawyer') {
    header("Location: login.php");
    exit();
}

$user_id   = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'];

// Flash message
$flash = $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);

// Get appointments assigned to this lawyer
$result = $conn->query(
    "SELECT a.*, c.full_name AS client_name, c.email AS client_email, c.phone AS client_phone
     FROM appointments a
     JOIN users c ON a.client_id = c.id
     WHERE a.lawyer_id = $user_id
     ORDER BY a.created_at DESC"
);
$appointments = [];
$counts = ['forwarded'=>0,'confirmed'=>0,'done'=>0];
while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
    if (isset($counts[$row['status']])) $counts[$row['status']]++;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lawyer Dashboard - LexConnect</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="dashboard-wrap">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-logo">LexConnect</div>
    <div class="sidebar-user">Lawyer</div>
    <div class="sidebar-name"><?php echo htmlspecialchars($full_name); ?></div>
    <nav class="sidebar-nav">
      <a href="lawyer_dashboard.php" class="active">⚖️ My Cases</a>
      <a href="index.php">🏠 Home</a>
      <a href="logout.php" class="logout-link">🚪 Logout</a>
    </nav>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="dash-content">

    <div class="dash-header">
      <h1>Lawyer Dashboard</h1>
      <p>Review cases forwarded to you and confirm consultations.</p>
    </div>

    <?php if ($flash): ?>
      <?php list($type, $msg) = explode(':', $flash, 2); ?>
      <div class="alert alert-<?php echo $type == 'success' ? 'success' : 'error'; ?>">
        <?php echo $type == 'success' ? '✅' : '⚠️'; ?> <?php echo htmlspecialchars($msg); ?>
      </div>
    <?php endif; ?>

    <!-- Stats -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-num"><?php echo array_sum($counts); ?></div>
        <div class="stat-label">Total Cases</div>
      </div>
      <div class="stat-card">
        <div class="stat-num"><?php echo $counts['forwarded']; ?></div>
        <div class="stat-label">Awaiting Review</div>
      </div>
      <div class="stat-card">
        <div class="stat-num"><?php echo $counts['confirmed']; ?></div>
        <div class="stat-label">Confirmed</div>
      </div>
      <div class="stat-card">
        <div class="stat-num"><?php echo $counts['done']; ?></div>
        <div class="stat-label">Completed</div>
      </div>
    </div>

    <!-- Cases -->
    <?php if (count($appointments) == 0): ?>
      <div class="card" style="text-align:center; padding: 3rem;">
        <p style="color:var(--gray-600); font-size:1.05rem;">No cases have been forwarded to you yet.</p>
      </div>
    <?php else: ?>
      <?php foreach ($appointments as $apt): ?>
        <div class="card" style="margin-bottom:1.5rem; border-left: 4px solid
          <?php
            if ($apt['status']=='forwarded')  echo 'var(--gold)';
            elseif ($apt['status']=='confirmed') echo 'var(--green)';
            else echo 'var(--gray-400)';
          ?>;">

          <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem; margin-bottom:1rem;">
            <div>
              <h3 style="font-family:var(--font-heading); font-size:1.2rem; color:var(--navy);">
                <?php echo htmlspecialchars($apt['client_name']); ?>
              </h3>
              <span style="font-size:0.85rem; color:var(--gray-600);">
                📧 <?php echo htmlspecialchars($apt['client_email']); ?>
                &nbsp;|&nbsp; 📞 <?php echo htmlspecialchars($apt['client_phone'] ?? 'N/A'); ?>
              </span>
            </div>
            <span class="badge badge-<?php echo $apt['status']; ?>"><?php echo ucfirst($apt['status']); ?></span>
          </div>

          <div style="margin-bottom:1.25rem;">
            <p style="font-size:0.82rem; font-weight:600; color:var(--navy); margin-bottom:0.35rem; text-transform:uppercase; letter-spacing:0.05em;">Client's Problem</p>
            <div class="problem-box"><?php echo nl2br(htmlspecialchars($apt['problem_description'])); ?></div>
          </div>

          <?php if ($apt['consultation_datetime']): ?>
            <div class="alert alert-success" style="margin-bottom:1rem;">
              📅 Consultation set for: <strong><?php echo date('l, d F Y \a\t H:i', strtotime($apt['consultation_datetime'])); ?></strong>
              <?php if ($apt['notes']): ?>
                <br><small>Notes: <?php echo htmlspecialchars($apt['notes']); ?></small>
              <?php endif; ?>
            </div>
          <?php endif; ?>

          <!-- Action: Confirm appointment -->
          <?php if ($apt['status'] == 'forwarded'): ?>
            <form method="POST" action="appointment.php">
              <input type="hidden" name="action" value="confirm">
              <input type="hidden" name="appointment_id" value="<?php echo $apt['id']; ?>">
              <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                <div class="form-group" style="margin:0;">
                  <label>Consultation Date & Time</label>
                  <input type="datetime-local" name="consultation_datetime" required>
                </div>
                <div class="form-group" style="margin:0;">
                  <label>Notes (optional)</label>
                  <input type="text" name="notes" placeholder="e.g. Bring your ID and documents">
                </div>
              </div>
              <button type="submit" class="btn btn-primary btn-sm">✅ Confirm Appointment</button>
            </form>
          <?php endif; ?>

          <!-- Action: Mark as done -->
          <?php if ($apt['status'] == 'confirmed'): ?>
            <form method="POST" action="appointment.php">
              <input type="hidden" name="action" value="done">
              <input type="hidden" name="appointment_id" value="<?php echo $apt['id']; ?>">
              <button type="submit" class="btn btn-dark btn-sm">🏁 Mark as Completed</button>
            </form>
          <?php endif; ?>

          <p style="font-size:0.8rem; color:var(--gray-400); margin-top:0.75rem;">
            Request submitted: <?php echo date('d M Y', strtotime($apt['created_at'])); ?>
          </p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

  </main>
</div>

</body>
</html>
