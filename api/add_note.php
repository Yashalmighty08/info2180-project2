<?php
// POST contact_id, comment -> JSON. Saves the note and touches the contact's updated_at. Owner: Janae.
require_once __DIR__ . '/../includes/bootstrap.php';
require_post();
require_login();

$contactId = post_str('contact_id');
$comment   = post_str('comment');

$errors = [];
if ($comment === '') { $errors['comment'] = 'Write a note first.'; }
elseif (mb_strlen($comment) > 2000) { $errors['comment'] = 'Keep the note under 2000 characters.'; }
if (!ctype_digit($contactId)) { json_fail('Contact not found.', [], 404); }
if ($errors) { json_fail('Please fix the errors.', $errors); }

$pdo = db();
$check = $pdo->prepare('SELECT id FROM contacts WHERE id = :id');
$check->bindParam(':id', $contactId);
$check->execute();
if (!$check->fetch()) { json_fail('Contact not found.', [], 404); }

$me = current_user()['id'];
$pdo->beginTransaction();
$ins = $pdo->prepare('INSERT INTO notes (contact_id, comment, created_by, created_at) VALUES (:c, :comment, :me, NOW())');
$ins->bindParam(':c', $contactId);
$ins->bindParam(':comment', $comment);
$ins->bindParam(':me', $me);
$ins->execute();
$noteId = (int) $pdo->lastInsertId();   
$upd = $pdo->prepare('UPDATE contacts SET updated_at = NOW() WHERE id = :id');
$upd->bindParam(':id', $contactId);
$upd->execute();
$pdo->commit();

json_ok('Note added.', ['id' => $noteId]);