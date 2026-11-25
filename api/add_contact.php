<?php
// Add Contact API - Part 3

require_once __DIR__ . '/../includes/bootstrap.php';

// Only accept POST requests
require_post();

// Only logged-in users can create contacts
require_login();

// Read the submitted form values
$title      = post_str('title');
$firstname  = post_str('firstname');
$lastname   = post_str('lastname');
$email      = post_str('email');
$telephone  = post_str('telephone');
$company    = post_str('company');
$type       = post_str('type');
$assignedTo = post_str('assigned_to');

// Store validation errors
$errors = [];


// Validate title
if (!in_array($title, ['Mr', 'Mrs', 'Ms', 'Dr', 'Prof'], true)) {
    $errors['title'] = 'Choose a title.';
}

// Validate first name
if ($firstname === '' || mb_strlen($firstname) > 50) {
    $errors['firstname'] = 'Enter a first name (50 characters or fewer).';
}

// Validate last name
if ($lastname === '' || mb_strlen($lastname) > 50) {
    $errors['lastname'] = 'Enter a last name (50 characters or fewer).';
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 100) {
    $errors['email'] = 'Enter a valid email address.';
}

// Validate telephone
if (!preg_match('/^[0-9+()\- ]{7,20}$/', $telephone)) {
    $errors['telephone'] = 'Enter a phone number (digits, spaces, + ( ) -).';
}

// Validate company
if ($company === '' || mb_strlen($company) > 100) {
    $errors['company'] = 'Enter a company (100 characters or fewer).';
}

// Validate contact type
if (!in_array($type, ['Sales Lead', 'Support'], true)) {
    $errors['type'] = 'Choose Sales Lead or Support.';
}


$assignedId = null;

if (!ctype_digit($assignedTo)) {
    $errors['assigned_to'] = 'Choose who the contact is assigned to.';
} else {
    $check = db()->prepare(
        'SELECT id FROM users WHERE id = :id'
    );

    $check->bindParam(':id', $assignedTo);
    $check->execute();

    if ($check->fetch()) {
        $assignedId = (int) $assignedTo;
    } else {
        $errors['assigned_to'] = 'That user does not exist.';
    }
}

// Stop if validation failed
if ($errors) {
    json_fail('Please fix the errors.', $errors);
}


// Get the currently logged-in user's ID
$me = current_user()['id'];

// Prepare the SQL INSERT statement
$stmt = db()->prepare(
    'INSERT INTO contacts
    (
        title,
        firstname,
        lastname,
        email,
        telephone,
        company,
        type,
        assigned_to,
        created_by,
        created_at,
        updated_at
    )
    VALUES
    (
        :title,
        :f,
        :l,
        :e,
        :t,
        :c,
        :type,
        :a,
        :me,
        NOW(),
        NOW()
    )'
);

// Bind submitted values to SQL parameters
$stmt->bindParam(':title', $title);
$stmt->bindParam(':f', $firstname);
$stmt->bindParam(':l', $lastname);
$stmt->bindParam(':e', $email);
$stmt->bindParam(':t', $telephone);
$stmt->bindParam(':c', $company);
$stmt->bindParam(':type', $type);
$stmt->bindParam(':a', $assignedId);
$stmt->bindParam(':me', $me);

// Execute the INSERT
$stmt->execute();

// Return the newly created contact ID
json_ok(
    'Contact added.',
    ['id' => (int) db()->lastInsertId()]
);


