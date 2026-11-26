<?php
// GET ?id=N -> one contact's full details as JSON. Owner: Part 4 (Contact details).
// Used by js/pages/contact.js every time the details need redrawing.
require_once __DIR__ . '/../includes/bootstrap.php';
require_login();

$contactId = get_str('id');
if (!ctype_digit($contactId)) {
    json_fail('Contact not found.', [], 404);
}

// users is joined twice: once for the assigned user (au), once for the creator (cu).
// LEFT JOIN keeps contacts that are not assigned to anyone (assigned_to is NULL).
$sql = "SELECT c.id, c.title, c.firstname, c.lastname, c.email, c.telephone, c.company,
               c.type, c.assigned_to, c.created_at, c.updated_at,
               CONCAT(au.firstname, ' ', au.lastname) AS assigned_name,
               CONCAT(cu.firstname, ' ', cu.lastname) AS creator_name
        FROM contacts AS c
        LEFT JOIN users AS au ON au.id = c.assigned_to
        LEFT JOIN users AS cu ON cu.id = c.created_by
        WHERE c.id = :id
        LIMIT 1";

$query = db()->prepare($sql);
$query->bindValue(':id', (int) $contactId, PDO::PARAM_INT);
$query->execute();
$contact = $query->fetch();

if ($contact === false) {
    json_fail('Contact not found.', [], 404);
}

$me = current_user();

// Keys match the data-field names in pages/contact.php, so the JS can copy them straight in.
json_ok('OK', [
    'id'             => (int) $contact['id'],
    'full_name'      => trim($contact['title'] . ' ' . $contact['firstname'] . ' ' . $contact['lastname']),
    'firstname'      => $contact['firstname'],
    'email'          => $contact['email'],
    'telephone'      => $contact['telephone'],
    'company'        => $contact['company'],
    'type'           => $contact['type'],
    'assigned_name'  => $contact['assigned_name'] ?? 'Nobody',
    'assigned_to_me' => $contact['assigned_to'] !== null && (int) $contact['assigned_to'] === (int) $me['id'],
    'created'        => 'Created on ' . fmt_date($contact['created_at']) . ' by ' . ($contact['creator_name'] ?? 'unknown'),
    'updated'        => 'Updated on ' . fmt_date($contact['updated_at']),
]);
