<?php

// Inialize session
session_start();

// Unset all session variables
session_unset();
// Delete all session variables
session_destroy();

// Jump to login page
header('Location: login.php');

?>