<?php
// The shell: header, menu and an empty #content area. Owner: shared kit (do not edit casually).
// Every other screen is a fragment in pages/ that app.js loads into #content without a refresh.
require_once __DIR__ . '/includes/bootstrap.php';
$user = current_user();
if ($user === null) {
    header('Location: login.php');
    exit;
}
// Developer shortcut: index.php?page=contact&id=3 opens that screen straight away,
// so you can test your own fragment without the other parts being finished.
$startPage = (isset($_GET['page']) && preg_match('/^[a-z_]+$/', $_GET['page'])) ? $_GET['page'] : 'dashboard';
$startParams = $_GET;
unset($startParams['page']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= h(csrf_token()) ?>">
    <title>Dolphin CRM</title>
    <link rel="stylesheet" href="css/layout.css">
</head>
<body>
    <header class="topbar">
        <span class="brand">&#128044; Dolphin CRM</span>
        <nav class="menu">
            <a href="#" data-page="dashboard">Home</a>
            <a href="#" data-page="new_contact">New Contact</a>
            <?php if ($user['role'] === 'Admin'): ?>
            <a href="#" data-page="users">Users</a>
            <a href="#" data-page="new_user">New User</a>
            <?php endif; ?>
            <a href="logout.php">Logout (<?= h($user['firstname']) ?>)</a>
        </nav>
    </header>
    <main id="content" class="container" aria-live="polite"></main>
    <footer class="footer">Copyright &copy; Dolphin CRM</footer>
    <script src="js/app.js"></script>
    <script>loadPage(<?= json_encode($startPage) ?>, <?= json_encode((object) $startParams) ?>);</script>
</body>
</html>
