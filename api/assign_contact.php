<?php
// POST id -> assigns the contact to whoever is logged in and touches updated_at. Owner: Part 4.
// The user id comes from the session, never from the request, so nobody can assign in someone else's name.
require_once __DIR__ . '/../includes/bootstrap.php';
require_post();
require_login();

$contactId = post_str('id');
if (!ctype_digit($contactId)) {
    json_fail('Contact not found.', [], 404);
}

// Confirm the contact exists first, so the reply is a clear 404 instead of a silent no-op.
$exists = db()->prepare('SELECT id FROM contacts WHERE id = :id');
$exists->bindValue(':id', (int) $contactId, PDO::PARAM_INT);
$exists->execute();
if ($exists->fetch() === false) {
    json_fail('Contact not found.', [], 404);
}

$me = current_user();

$update = db()->prepare('UPDATE contacts SET assigned_to = :user_id, updated_at = NOW() WHERE id = :id');
$update->bindValue(':user_id', (int) $me['id'], PDO::PARAM_INT);
$update->bindValue(':id', (int) $contactId, PDO::PARAM_INT);
$update->execute();

json_ok('Contact assigned to you.', ['assigned_name' => $me['firstname'] . ' ' . $me['lastname']]);
