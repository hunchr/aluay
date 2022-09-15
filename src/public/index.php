<?php
/**
 * Handles all requested PHP files.
 */
session_start();
header('X-Content-Type-Options: nosniff');

$uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;
$uri = $url = $_SERVER['REQUEST_URI'];
$rx = '/(?<=\/[@&])[a-z-]{2,20}|(?<=\/[a-z])\d{1,20}/';

// Remove usernames and IDs
if (preg_match_all($rx, $uri, $q)) {
    preg_replace($rx, '', $uri);
    $q = $q[0];
}

// Add file extension
$uri .= substr($uri, -1) === '/' ? 'index.php' : '.php';

if (/*preg_match('/\.{2,}/', $uri) || */!file_exists('../pages'.$uri)) {
    require '../include/error.php';
}

if (substr($uri, 0, 5) === '/api/') {
    exit('api');
}

// --- Get script and language file ---
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = require '../include/auth.php';
}

$lang = $_SESSION['lang'];
$is_fetch = isset($_POST['is_fetch']);

if (file_exists('../lang/'.$lang.$uri)) {
    require '../lang/'.$lang.$uri;
}

require '../pages'.$uri;
?>
