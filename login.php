<?php
// The login page (a full page, not a fragment). Owner: Member 1.
require_once __DIR__ . '/includes/bootstrap.php';
if (current_user() !== null) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Dolphin CRM</title>
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body class="login-body">
    <header class="topbar"><span class="brand">&#128044; Dolphin CRM</span></header>
    <main class="login-box">
        <h1>Login</h1>
        <form id="login-form" novalidate>
            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
            <div class="field">
                <input type="email" name="email" id="email" placeholder="Email address">
                <span class="field-error" data-for="email"></span>
            </div>
            <div class="field">
                <input type="password" name="password" id="password" placeholder="Password">
                <span class="field-error" data-for="password"></span>
            </div>
            <button type="submit" class="btn btn-block">Login</button>
            <div class="message" id="login-message" role="alert"></div>
        </form>
    </main>
    <footer class="footer">Copyright &copy; Dolphin CRM</footer>
    <script src="js/login.js"></script>
</body>
</html>
