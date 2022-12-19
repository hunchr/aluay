<?php
/**
 * Shows the error page.
 */
if (isset($conn)) {
    $conn -> close();
}

$html =
'<span>'.$l['description'].'</span>
<img src="/img/bg/peaks-'.random_int(0, 9).'.svg" alt="'.$l['bg_alt'].'">';

return 'error center';
?>
