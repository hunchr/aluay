<?php
/**
 * Shows the error page.
 */
if (isset($conn)) {
    $conn -> close();
}

$main = $l[1];

send('error center');
?>
