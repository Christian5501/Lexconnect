<?php
// appointment.php - Handles all appointment actions (create, forward, confirm, done)
session_start();
require 'db.php';

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$action = $_POST['action'] ?? '';
$role   = $_SESSION['role'];

// ─── CLIENT: Create new appointment ───────────────────────────────
if ($action == 'create' && $role == 'client') {
    $client_id   = $_SESSION['user_id'];
    $description = htmlspecialchars(trim($_POST['problem_description']));

    if (empty($description)) {
        $_SESSION['msg'] = "error:Please describe your legal problem.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO appointments (client_id, problem_description, status) VALUES (?, ?, 'pending')"
        );
        $stmt->bind_param("is", $client_id, $description);
        if ($stmt->execute()) {
            $_SESSION['msg'] = "success:Your appointment request has been submitted!";
        } else {
            $_SESSION['msg'] = "error:Could not submit request. Try again.";
        }
    }
    header("Location: client_dashboard.php");
    exit();
}

// ─── SECRETARY: Forward appointment to a lawyer ───────────────────
if ($action == 'forward' && $role == 'secretary') {
    $appointment_id = intval($_POST['appointment_id']);
    $lawyer_id      = intval($_POST['lawyer_id']);
    $secretary_id   = $_SESSION['user_id'];

    $stmt = $conn->prepare(
        "UPDATE appointments SET lawyer_id=?, secretary_id=?, status='forwarded' WHERE id=?"
    );
    $stmt->bind_param("iii", $lawyer_id, $secretary_id, $appointment_id);
    if ($stmt->execute()) {
        $_SESSION['msg'] = "success:Appointment forwarded to lawyer.";
    } else {
        $_SESSION['msg'] = "error:Could not forward appointment.";
    }
    header("Location: secretary_dashboard.php");
    exit();
}

// ─── LAWYER: Confirm appointment and set consultation time ────────
if ($action == 'confirm' && $role == 'lawyer') {
    $appointment_id      = intval($_POST['appointment_id']);
    $consultation_datetime = $_POST['consultation_datetime'];
    $notes               = htmlspecialchars(trim($_POST['notes']));

    $stmt = $conn->prepare(
        "UPDATE appointments SET status='confirmed', consultation_datetime=?, notes=? WHERE id=?"
    );
    $stmt->bind_param("ssi", $consultation_datetime, $notes, $appointment_id);
    if ($stmt->execute()) {
        $_SESSION['msg'] = "success:Appointment confirmed! Consultation date set.";
    } else {
        $_SESSION['msg'] = "error:Could not confirm appointment.";
    }
    header("Location: lawyer_dashboard.php");
    exit();
}

// ─── LAWYER: Mark appointment as done ────────────────────────────
if ($action == 'done' && $role == 'lawyer') {
    $appointment_id = intval($_POST['appointment_id']);

    $stmt = $conn->prepare("UPDATE appointments SET status='done' WHERE id=?");
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();

    $_SESSION['msg'] = "success:Appointment marked as completed.";
    header("Location: lawyer_dashboard.php");
    exit();
}

// If nothing matched, go back to home
header("Location: index.php");
exit();
?>
