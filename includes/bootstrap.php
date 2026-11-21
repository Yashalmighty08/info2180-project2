<?php
// The ONE include every page and api file starts with:
//   require_once __DIR__ . '/../includes/bootstrap.php';
// It gives you db(), h(), post_str(), json_ok(), json_fail(), current_user(), require_login(), require_admin().
// Owner: Member 1.
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';
