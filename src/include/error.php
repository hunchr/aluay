<?php
/**
 * Shows the error page.
 */
if (isset($conn)) {
    $conn -> close();
}

$main =
'<main class="error center vis" data-title="'.$l[0].'" data-url="'.substr($url, 1).'">
    '.$l[1].'
</main>';

// Generate HTML
$is_fetch ? exit($main) : require '../include/html.php';
html('main', 'social');
?>
