<?php
/**
 * Sends a JSON response.
 */
header('Content-Type: application/json; charset=utf-8');

function send($msg, $http_code) {
    exit('{"message":"'.$msg.'","status":'.$http_code.'}');
};
?>
