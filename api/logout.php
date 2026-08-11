<?php
require_once __DIR__ . '/../includes/session_check.php';

$_SESSION = [];
session_destroy();

header('Location: ../index.html');
exit;
