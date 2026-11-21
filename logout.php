<?php
// logout.php - Destroy session and redirect to home
session_start();
session_destroy();
header("Location: index.php");
exit();
?>
