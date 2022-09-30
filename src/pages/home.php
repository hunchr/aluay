<?php
/**
 * Shows posts from liked users and communities.
 */
$main = // TODO
'<main class="center vis" data-title="'.$l[0].'" data-url="home">
    HOME - COMING SOON
</main>';

// ----- Generate HTML -----
$is_fetch ? exit($main) : require '../include/html.php';
html('main', 'social');
?>
