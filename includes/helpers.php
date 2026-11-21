<?php
// Small shared helpers. Owner: Member 1. Do not rename these functions.

// Escape a value for safe output inside HTML. In a page write: echo h($row['firstname']);
function h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Read a POST/GET value as trimmed text ('' if missing).
function post_str(string $key): string
{
    return isset($_POST[$key]) ? trim((string) $_POST[$key]) : '';
}
function get_str(string $key): string
{
    return isset($_GET[$key]) ? trim((string) $_GET[$key]) : '';
}

// Send a JSON reply and stop. Every api/*.php file answers with ONE of these two:
//   json_ok('Contact added.', ['id' => 7]);
//   json_fail('Please fix the errors.', ['email' => 'Email is not valid.']);
function json_out(array $payload, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($payload);
    exit;
}
function json_ok(string $message, array $data = []): void
{
    json_out(['success' => true, 'message' => $message, 'errors' => new stdClass(), 'data' => $data]);
}
function json_fail(string $message, array $errors = [], int $status = 422): void
{
    json_out(['success' => false, 'message' => $message, 'errors' => (object) $errors, 'data' => new stdClass()], $status);
}

// Put this first in every api file that changes data:  require_post();
// It only allows POST requests and checks the CSRF token that apiPost() sends (lecture 8).
function require_post(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        json_fail('Use POST for this request.', [], 405);
    }
    if (!csrf_ok()) {
        json_fail('Security check failed. Reload the page and try again.', [], 403);
    }
}

// Friendly date for screens:  fmt_date('2026-11-04 09:00:00')  ->  Nov 4, 2026 9:00 AM
function fmt_date($datetime): string
{
    $time = strtotime((string) $datetime);
    return $time ? date('M j, Y g:i A', $time) : '';
}
