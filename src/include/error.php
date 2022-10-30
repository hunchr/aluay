<?php
/**
 * Shows the error page.
 */
if (isset($conn)) {
    $conn -> close();
}

$main =
'<span>'.$l[1].'</span>
<img src="/img/bg/peaks-'.random_int(0, 9).'.svg" alt="'.$l[3].'">';

send('error');
?>
