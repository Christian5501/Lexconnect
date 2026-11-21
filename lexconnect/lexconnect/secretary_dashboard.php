<?php
// secretary_dashboard.php - Secretary Dashboard
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'secretary') {
    header("Location: login.php");
    exit();
}

$full_name = $_SESSION['full_name'];

// Flash message
$flash = $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);

// Get all appointments
$all = $conn->query(
    "SELECT a.*, 
            c.full_name AS client_name, c.email AS client_email, c.phone AS client_phone,
            l.full_name AS lawyer_name
     FROM appointments a
     JOIN users c ON a.client_id = c.id
     LEFT JOIN users l ON a.lawyer_id = l.id
     ORDER BY a.created_at DESC"
);
$appointments = [];
$counts = ['pending'=>0,'forwarded'=>0,'confirmed'=>0,'done'=>0];
while ($row = $all->fetch_assoc()) {
    $appointments[] = $row;
    if (isset($counts[$row['status']])) $counts[$row['status']]++;
}

// Get list of lawyers for forwarding
$lawyers_result = $conn->query("SELECT id, full_name FROM users WHERE role='lawyer' ORDER BY full_name");
$lawyers = [];
while ($row = $lawyers_result->fetch_assoc()) {
    $lawyers[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Secretary Dashboard - LexConnect</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="dashboard-wrap">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-logo">LexConnect</div>
    <div class="sidebar-user">Secretary</div>
    <div class="sidebar-name"><?php echo htmlspecialchars($full_name); ?></div>
    <nav class="sidebar-nav">
      <a href="secretary_dashboard.php" class="active">📋 All Requests</a>
      <a href="index.php">🏠 Home</a>
      <a href="logout.php" class="logout-link">🚪 Logout</a>
    </nav>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="dash-content">

    <div class="dash-header">
      <h1>Secretary Dashboard</h1>
      <p>Review incoming appointment requests and forward them to the right lawyer.</p>
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
        <div class="stat-label">Total Requests</div>
      </div>
      <div class="stat-card">
        <div class="stat-num"><?php echo $counts['pending']; ?></div>
        <div class="stat-label">Pending</div>
      </div>
      <div class="stat-card">
        <div class="stat-num"><?php echo $counts['forwarded']; ?></div>
        <div class="stat-label">Forwarded</div>
      </div>
      <div class="stat-card">
        <div class="stat-num"><?php echo $counts['confirmed'] + $counts['done']; ?></div>
        <div class="stat-label">Resolved</div>
      </div>
    </div>

    <!-- Appointments Table -->
    <div class="table-wrap">
      <div class="table-head">
        <h3>All Appointment Requests</h3>
      </div>
      <?php if (count($appointments) == 0): ?>
        <p style="padding: 2rem; color: var(--gray-600); text-align: center;">No appointments yet.</p>
      <?php else: ?>
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Client</th>
              <th>Problem Description</th>
              <th>Status</th>
              <th>Lawyer</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($appointments as $i => $apt): ?>
              <tr>
                <td><?php echo $i + 1; ?></td>
                <td>
                  <strong style="color:var(--navy)"><?php echo htmlspecialchars($apt['client_name']); ?></strong><br>
                  <span style="font-size:0.82rem;color:var(--gray-600)"><?php echo htmlspecialchars($apt['client_email']); ?></span><br>
                  <span style="font-size:0.82rem;color:var(--gray-600)"><?php echo htmlspecialchars($apt['client_phone'] ?? ''); ?></span>
                </td>
                <td>
                  <div class="problem-box"><?php echo htmlspecialchars(substr($apt['problem_description'], 0, 100)) . '...'; ?></div>
                </td>
                <td><span class="badge badge-<?php echo $apt['status']; ?>"><?php echo ucfirst($apt['status']); ?></span></td>
                <td><?php echo $apt['lawyer_name'] ? htmlspecialchars($apt['lawyer_name']) : '<span style="color:var(--gray-400)">Not assigned</span>'; ?></td>
                <td>
                  <?php if ($apt['status'] == 'pending'): ?>
                    <form method="POST" action="appointment.php" style="display:flex;gap:0.5rem;flex-wrap:wrap;align-items:center;">
                      <input type="hidden" name="action" value="forward">
                      <input type="hidden" name="appointment_id" value="<?php echo $apt['id']; ?>">
                      <select name="lawyer_id" required style="padding:0.4rem 0.6rem;border-radius:6px;border:1.5px solid var(--gray-200);font-size:0.85rem;">
                        <option value="">Select Lawyer</option>
                        <?php foreach ($lawyers as $lawyer): ?>
                          <option value="<?php echo $lawyer['id']; ?>"><?php echo htmlspecialchars($lawyer['full_name']); ?></option>
                        <?php endforeach; ?>
                      </select>
                      <button type="submit" class="btn btn-dark btn-sm">Forward</button>
                    </form>
                  <?php else: ?>
                    <span style="font-size:0.85rem;color:var(--gray-400)">Already forwarded</span>
                  <?php endif; ?>
                </td>
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
