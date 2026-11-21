<?php
// Sessions and access checks. Owner: Member 1. Do not rename these functions.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lecture 8: expire the session after 30 minutes of inactivity.
const IDLE_LIMIT_SECONDS = 1800;
if (isset($_SESSION['user'])) {
    if (isset($_SESSION['last_active']) && time() - $_SESSION['last_active'] > IDLE_LIMIT_SECONDS) {
        $_SESSION = [];
        session_regenerate_id(true);
    } else {
        $_SESSION['last_active'] = time();
    }
}

// Lecture 8 (CSRF): one random token per session. index.php and login.php hand it to the browser,
// app.js sends it with every apiPost(), and require_post() checks it. You do not write any of this.
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function csrf_ok(): bool
{
    $sent = $_POST['csrf_token'] ?? '';
    return is_string($sent) && $sent !== '' && hash_equals(csrf_token(), $sent);
}

// The logged-in user, or null. Keys: id, firstname, lastname, email, role
function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

// Put this at the top of every page and api file that needs a login.
function require_login(): void
{
    if (current_user() === null) {
        json_out(['success' => false, 'message' => 'Please log in.', 'errors' => new stdClass(), 'data' => new stdClass()], 401);
    }
}

// Put this at the top of Admin-only pages and api files (it also checks the login).
function require_admin(): void
{
    require_login();
    if (current_user()['role'] !== 'Admin') {
        json_out(['success' => false, 'message' => 'Only an Admin can do this.', 'errors' => new stdClass(), 'data' => new stdClass()], 403);
    }
}
