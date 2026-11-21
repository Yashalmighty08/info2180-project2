<?php
// Destroy the session and go to the login page. Owner: Member 1.
require_once __DIR__ . '/includes/bootstrap.php';
$_SESSION = [];
session_destroy();
header('Location: login.php');
exit;
