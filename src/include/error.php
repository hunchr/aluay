<?php
/**
 * Shows the error page.
 */
if (isset($conn)) {
    $conn -> close();
}

$main =
'<main class="center error vis" data-title="'.$l[0].'" data-url="'.$url.'">
    '.$l[1].'
</main>';

// Generate HTML
$is_fetch ? exit($main) : require '../include/html.php';
html('main', 'social');
?>
