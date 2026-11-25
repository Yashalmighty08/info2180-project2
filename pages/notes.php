<?php
// The notes box (a fragment loaded inside the contact details page). Owner: Janae.
require_once __DIR__ . '/../includes/bootstrap.php';
require_login();
$contactId = (int) get_str('contact_id');
$stmt = db()->prepare(
    'SELECT n.comment, n.created_at, CONCAT(u.firstname, " ", u.lastname) AS author
     FROM notes n LEFT JOIN users u ON u.id = n.created_by
     WHERE n.contact_id = :id ORDER BY n.created_at DESC, n.id DESC'
);
$stmt->bindParam(':id', $contactId);
$stmt->execute();
$notes = $stmt->fetchAll();
?>
<div class="card notes">
    <h2>Notes</h2>
    <?php if (!$notes): ?>
        <p class="muted">No notes yet.</p>
    <?php endif; ?>
    <?php foreach ($notes as $n): ?>
        <div class="note">
            <strong><?= h($n['author'] ?? 'Unknown') ?></strong>
            <span class="muted"><?= h(fmt_date($n['created_at'])) ?></span>
            <p><?= nl2br(h($n['comment'])) ?></p>
        </div>
    <?php endforeach; ?>
    <form id="note-form" novalidate>
        <input type="hidden" name="contact_id" value="<?= $contactId ?>">
        <div class="field">
            <label for="comment">Add a note about this contact</label>
            <textarea id="comment" name="comment" rows="3"></textarea>
            <span class="field-error" data-for="comment"></span>
        </div>
        <button type="submit" class="btn">Add Note</button>
        <div class="message" id="note-message" role="alert"></div>
    </form>
</div>