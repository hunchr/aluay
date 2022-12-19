<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

$uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;
$uri = substr($_SERVER['REQUEST_URI'], 4);
$regex = '/(?<=\/\W)[a-z-]+|(?<=\/[a-z])\d+/';

// Remove usernames and IDs
if (preg_match_all($regex, $uri, $q)) {
    $uri = preg_replace($regex, '', $uri);
    $q = $q[0];
}

// Send JSON
function send($http, $msg) {
    global $json;

    $json['message'] = $msg;
    $json['status'] = $http;

    exit(json_encode($json));
};

// Get model
$uri = '../../models'.$uri.'.php';
file_exists($uri) ? require $uri : send(404, 'Not Found');
send(200, 'OK');
?>
