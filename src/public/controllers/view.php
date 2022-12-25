<?php
header('Content-Type: text/html; charset=utf-8');
// ini_set('session.cookie_secure', '1');
// ini_set('session.cookie_samesite', 'Strict');
session_start();

$uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;
$lang = $uid ? $_SESSION['lang'] : require '../../models/@include/auth.php';
$uri = $url = $_SERVER['REQUEST_URI'];
$regex = '/(?<=\/\W)[a-z-]+|(?<=\/[a-z])\d+/';

// Remove usernames and IDs
if (preg_match_all($regex, $uri, $q)) {
    $uri = preg_replace($regex, '', $uri);
    $q = $q[0];
}

// Receive model data
function send($http, $reason = false) {
    global $json;
    $json['reason'] = $reason;
    $json['status'] = $http;
};

// Login required
function login_req() {
    global $uid;

    if (!$uid) {
        header('Location: /login');
        exit();
    }
};

// Insert icon
function icon($name) {
    return '<span class="i" aria-hidden="true">'.$name.'</span>';
};

// Get model
if (file_exists('../../models'.$uri.'.php')) {
    require '../../models'.$uri.'.php';
}

// Get view
require '../../locales/'.$lang.(file_exists('../../locales/'.$lang.$uri.'.php') ? $uri : '/@include/error').'.php';
$class = require '../../views'.(file_exists('../../views'.$uri.'.php') ? $uri : '/@include/error').'.php';
$html = '<main class="'.$class.'" data-meta="'.$url.'|'.$l['title'].'">'.$html.'</main>';

isset($_POST['fetch']) ? exit($html) : require '../../views/@include/send.php';
?>
