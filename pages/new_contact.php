<?php
// The "new contact" form (a fragment). Owner: Part 3.

require_once __DIR__ . '/../includes/bootstrap.php';

require_login();

$users = db()->query(
    'SELECT id, firstname, lastname
     FROM users
     ORDER BY firstname, lastname'
)->fetchAll();

$me = current_user()['id'];
?>


<div class="page-head">
    <h1>New Contact</h1>
</div>

<form id="contact-form" class="card" novalidate>

    <div class="field">
        <label for="title">Title</label>
        <select id="title" name="title">
            <option>Mr</option>
            <option>Mrs</option>
            <option>Ms</option>
            <option>Dr</option>
            <option>Prof</option>
        </select>
        <span class="field-error" data-for="title"></span>
    </div>

    <div class="field">
        <label for="firstname">First Name</label>
        <input type="text" id="firstname" name="firstname">
        <span class="field-error" data-for="firstname"></span>
    </div>

    <div class="field">
        <label for="lastname">Last Name</label>
        <input type="text" id="lastname" name="lastname">
        <span class="field-error" data-for="lastname"></span>
    </div>

    <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email">
        <span class="field-error" data-for="email"></span>
    </div>

    <div class="field">
        <label for="telephone">Telephone</label>
        <input type="tel" id="telephone" name="telephone">
        <span class="field-error" data-for="telephone"></span>
    </div>

    <div class="field">
        <label for="company">Company</label>
        <input type="text" id="company" name="company">
        <span class="field-error" data-for="company"></span>
    </div>

    <div class="field">
        <label for="type">Type</label>
        <select id="type" name="type">
            <option>Sales Lead</option>
            <option>Support</option>
        </select>
        <span class="field-error" data-for="type"></span>
    </div>

    <div class="field">
        <label for="assigned_to">Assigned To</label>

        <select id="assigned_to" name="assigned_to">
            <?php foreach ($users as $u): ?>
                <option
                    value="<?= (int) $u['id'] ?>"
                    <?= (int) $u['id'] === (int) $me ? 'selected' : '' ?>
                >
                    <?= h($u['firstname'] . ' ' . $u['lastname']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <span class="field-error" data-for="assigned_to"></span>
    </div>

    <button type="submit" class="btn">Save</button>

    <div class="message" id="contact-message" role="alert"></div>

</form>

