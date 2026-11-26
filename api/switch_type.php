<?php
// POST id -> flips the contact between 'Sales Lead' and 'Support' and touches updated_at. Owner: Part 4.
require_once __DIR__ . '/../includes/bootstrap.php';
require_post();
require_login();

$contactId = post_str('id');
if (!ctype_digit($contactId)) {
    json_fail('Contact not found.', [], 404);
}

// The flip happens inside one UPDATE, so two clicks at the same time can't leave a half-changed row.
$update = db()->prepare(
    "UPDATE contacts
     SET type = CASE WHEN type = 'Sales Lead' THEN 'Support' ELSE 'Sales Lead' END,
         updated_at = NOW()
     WHERE id = :id"
);
$update->bindValue(':id', (int) $contactId, PDO::PARAM_INT);
$update->execute();

// The type always changes, so 0 affected rows can only mean the contact doesn't exist.
if ($update->rowCount() === 0) {
    json_fail('Contact not found.', [], 404);
}

$read = db()->prepare('SELECT type FROM contacts WHERE id = :id');
$read->bindValue(':id', (int) $contactId, PDO::PARAM_INT);
$read->execute();
$newType = $read->fetchColumn();

json_ok('Contact is now ' . $newType . '.', ['type' => $newType]);
