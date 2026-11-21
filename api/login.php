<?php
// POST email, password -> JSON. Owner: Member 1.
require_once __DIR__ . '/../includes/bootstrap.php';
require_post();

$email    = post_str('email');
$password = isset($_POST['password']) ? (string) $_POST['password'] : '';

$errors = [];
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Enter a valid email address.';
}
if ($password === '') {
    $errors['password'] = 'Enter your password.';
}
if ($errors) {
    json_fail('Please fix the errors.', $errors);
}

$stmt = db()->prepare('SELECT id, firstname, lastname, email, password, role FROM users WHERE email = :email');
$stmt->bindParam(':email', $email);
$stmt->execute();
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    json_fail('Wrong email or password.', [], 401);
}

session_regenerate_id(true);
$_SESSION['user'] = [
    'id'        => (int) $user['id'],
    'firstname' => $user['firstname'],
    'lastname'  => $user['lastname'],
    'email'     => $user['email'],
    'role'      => $user['role'],
];

json_ok('Welcome back, ' . $user['firstname'] . '.', ['redirect' => 'index.php']);
