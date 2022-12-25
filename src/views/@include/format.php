<?php
/**
 * Formats numbers and dates.
 */
// Format number (e.g. 1000 => 1K)
function fnumber($n) {
    return
         $n < 1e3 ? $n:
        ($n < 1e6 ? (floor($n / 1e2) / 10).'K':
        (floor($n / 1e5) / 10).'M');
};

// Format date
function fdate($ts) {
    $s = time() - $ts;

    return (
        $s > 3.154e7 ? round($s / 3.154e7).'y' :
        ($s > 86400 ? round($s / 86400).'d' :
        ($s > 3600 ? round($s / 3600).'h' :
        round($s / 60).'i\min'))
    );
};
?>
