<?php
/**
 * Handles all requested PHP files.
 */
session_start();
header('Content-Type: text/html; charset=utf-8');

// Use SVG icons
function svg($id) {
    return '<svg viewBox="0 0 8 8"><use href="#i-'.$id.'"></use></svg>';
};

// Send HTML to frondend
function send($class) {
    global $l;
    global $url;
    global $main;

    $main = '<main class="'.$class.'" data-meta="'.$url.'|'.$l[0].'">'.$main.'</main>';

    isset($_POST['fetch']) ? exit($main) : require '../include/html.php';    
    html();
};

$url = $uri = $_SERVER['REQUEST_URI'].'.php';
$rx = '/(?<=\/[@&])[a-z-]{2,20}|(?<=\/[a-z])\d{1,20}/';

// Remove usernames and IDs
if (preg_match_all($rx, $uri, $q)) {
    $uri = preg_replace($rx, '', $uri);
    $q = $q[0];
}

// Get browser language
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = require '../include/auth.php';
}

$uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;
$lang = $_SESSION['lang'];

// Check if page exists
if (!file_exists('../pages'.$uri)) {
    require '../lang/'.$lang.'/include/404.php';
    require '../include/error.php';
}

// Get language file
if (file_exists('../lang/'.$lang.$uri)) {
    require '../lang/'.$lang.$uri;
}

// Get file
require '../pages'.$uri;
?>
