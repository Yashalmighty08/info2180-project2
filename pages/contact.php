<?php
// Contact details screen (a fragment loaded into #content). Owner: Part 4.
// This file is only the skeleton: js/pages/contact.js fills every data-field from api/contact_get.php.
require_once __DIR__ . '/../includes/bootstrap.php';
require_login();
$contactId = (int) get_str('id');
?>
<div id="contact" class="contact-view" data-id="<?= $contactId ?>">
    <div class="page-head contact-head">
        <div class="contact-title">
            <span class="contact-avatar" aria-hidden="true">&#128100;</span>
            <div>
                <h1 data-field="full_name">Loading...</h1>
                <p class="contact-meta muted">
                    <span data-field="created"></span><br>
                    <span data-field="updated"></span>
                </p>
            </div>
        </div>
        <div class="contact-actions">
            <button type="button" class="btn btn-assign" id="assign-btn">Assign to me</button>
            <button type="button" class="btn btn-switch" id="switch-btn">Switch type</button>
        </div>
    </div>

    <div class="message" id="contact-msg" role="alert"></div>

    <div class="card contact-card">
        <dl class="contact-grid">
            <div><dt>Email</dt><dd data-field="email"></dd></div>
            <div><dt>Telephone</dt><dd data-field="telephone"></dd></div>
            <div><dt>Company</dt><dd data-field="company"></dd></div>
            <div><dt>Assigned To</dt><dd data-field="assigned_name"></dd></div>
            <div><dt>Type</dt><dd><span class="badge" data-field="type"></span></dd></div>
        </dl>
    </div>

    <!-- Agreement with Part 5 (Notes): keep this id and data attribute exactly as they are. -->
    <section id="notes-section" data-contact-id="<?= $contactId ?>"></section>
</div>
