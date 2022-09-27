<?php
/**
 * Shows posts from liked users and communities.
 */
// exit('<code>'.$_SERVER['REQUEST_URI'].'</code> is coming soon');

// ----- Generate HTML -----
$is_fetch ? exit($main) : require '../include/html.php';
html('main', 'social');
?>
