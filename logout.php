<?php
// Start the session to access session data
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Prevent caching of the page to ensure the UI updates correctly
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Redirect the user to the homepage
header("Location: index.php");
exit;
?>
