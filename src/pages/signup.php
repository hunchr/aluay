<?php
/**
 * Shows the signup form.
 */
$main = 
'<main class="center form vis">
    <input type="text" placeholder="'.$l[3].'" maxlength="20" spellcheck="false" autocomplete="off">
    <input type="email" placeholder="'.$l[4].'" maxlength="100" spellcheck="false" autocomplete="off">
    <input type="password" placeholder="'.$l[5].'" maxlength="1000" autocomplete="new-password">
    <input type="password" placeholder="'.$l[6].'" maxlength="1000" autocomplete="new-password">
    <button class="btn">'.$l[7].'</button>
    <span>'.$l[8].'&nbsp;<button class="a">'.$l[9].'</button></span>
</main>';

// Generate HTML
$is_fetch ? exit($main) : require '../include/html.php';
html('main', 'social');
// ?>
