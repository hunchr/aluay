<?php
/**
 * Shows the error page.
 */
if (isset($conn)) {
    $conn -> close();
}

$main =
'<span>'.$l['description'].'</span>
<img src="/img/bg/peaks-'.random_int(0, 9).'.svg" alt="'.$l['bg_alt'].'">';

send('error');
?>
