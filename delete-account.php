<?php
require './includes/library.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$pdo = connectDB();

// Fetch user ID from the database based on the current session
$username = $_SESSION['username'];
$stmt = $pdo->prepare("SELECT id FROM 3420_assg_users WHERE username = ?");
$stmt->execute([$username]);
$userID = $stmt->fetchColumn();

// Delete all data associated with the user
$stmtDeleteLists = $pdo->prepare("DELETE FROM 3420_assg_lists WHERE user_id = ?");
$stmtDeleteLists->execute([$userID]);

$stmtDeleteUser = $pdo->prepare("DELETE FROM 3420_assg_users WHERE username = ?");
$stmtDeleteUser->execute([$username]);

// Destroy the session
session_destroy();

// Redirect to login
header("Location: login.php");
exit();
?>
<!-- Rest of the HTML code -->
