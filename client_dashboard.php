<?php
// client_dashboard.php - Client dashboard
session_start();
require 'db.php';

// Only clients can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'client') {
    header("Location: login.php");
    exit();
}

$user_id   = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'];

// Get flash message
$flash = $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);

// Get all appointments for this client
$result = $conn->query(
    "SELECT a.*, u.full_name AS lawyer_name
     FROM appointments a
     LEFT JOIN users u ON a.lawyer_id = u.id
     WHERE a.client_id = $user_id
     ORDER BY a.created_at DESC"
);

// Count by status
$counts = ['pending' => 0, 'forwarded' => 0, 'confirmed' => 0, 'done' => 0];
$appointments = [];
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
  <title>My Dashboard - LexConnect</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="dashboard-wrap">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-logo">LexConnect</div>
    <div class="sidebar-user">Logged in as</div>
    <div class="sidebar-name"><?php echo htmlspecialchars($full_name); ?></div>
    <nav class="sidebar-nav">
      <a href="client_dashboard.php" class="active">📋 My Appointments</a>
      <a href="index.php">🏠 Home</a>
      <a href="about.php">ℹ️ About Us</a>
      <a href="contact.php">📞 Contact</a>
      <a href="logout.php" class="logout-link">🚪 Logout</a>
    </nav>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="dash-content">

    <div class="dash-header">
      <h1>My Dashboard</h1>
      <p>Welcome back, <?php echo htmlspecialchars($full_name); ?>. Manage your legal appointments here.</p>
    </div>

    <!-- Flash message -->
    <?php if ($flash): ?>
      <?php list($type, $msg) = explode(':', $flash, 2); ?>
      <div class="alert alert-<?php echo $type == 'success' ? 'success' : 'error'; ?>">
        <?php echo $type == 'success' ? '✅' : '⚠️'; ?> <?php echo htmlspecialchars($msg); ?>
      </div>
    <?php endif; ?>

    <!-- Stats -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-num"><?php echo $counts['pending']; ?></div>
        <div class="stat-label">Pending</div>
      </div>
      <div class="stat-card">
        <div class="stat-num"><?php echo $counts['forwarded']; ?></div>
        <div class="stat-label">Forwarded</div>
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

    <!-- New Appointment Form -->
    <div class="card" style="margin-bottom: 2rem; border-top: 3px solid var(--gold);">
      <h3 style="font-family: var(--font-heading); font-size: 1.3rem; color: var(--navy); margin-bottom: 1rem;">
        ➕ Request New Appointment
      </h3>
      <form method="POST" action="appointment.php">
        <input type="hidden" name="action" value="create">
        <div class="form-group">
          <label for="problem_description">Describe Your Legal Problem</label>
          <textarea id="problem_description" name="problem_description"
            placeholder="Briefly explain your legal issue. For example: I have a land dispute with my neighbour over a boundary issue..."
            required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Appointment Request</button>
      </form>
    </div>

    <!-- Appointments Table -->
    <div class="table-wrap">
      <div class="table-head">
        <h3>My Appointment Requests</h3>
      </div>
      <?php if (count($appointments) == 0): ?>
        <p style="padding: 2rem; color: var(--gray-600); text-align: center;">
          You have not submitted any appointments yet.
        </p>
      <?php else: ?>
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Problem Description</th>
              <th>Lawyer</th>
              <th>Status</th>
              <th>Consultation Date</th>
              <th>Submitted</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($appointments as $i => $apt): ?>
              <tr>
                <td><?php echo $i + 1; ?></td>
                <td>
                  <div class="problem-box"><?php echo htmlspecialchars(substr($apt['problem_description'], 0, 120)) . (strlen($apt['problem_description']) > 120 ? '...' : ''); ?></div>
                </td>
                <td><?php echo $apt['lawyer_name'] ? htmlspecialchars($apt['lawyer_name']) : '<span style="color:var(--gray-400)">Not assigned yet</span>'; ?></td>
                <td><span class="badge badge-<?php echo $apt['status']; ?>"><?php echo ucfirst($apt['status']); ?></span></td>
                <td><?php echo $apt['consultation_datetime'] ? date('D, d M Y H:i', strtotime($apt['consultation_datetime'])) : '<span style="color:var(--gray-400)">—</span>'; ?></td>
                <td style="color:var(--gray-600); font-size:0.85rem;"><?php echo date('d M Y', strtotime($apt['created_at'])); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>

  </main>
</div>

</body>
</html>
