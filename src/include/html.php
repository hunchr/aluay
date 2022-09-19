<?php
/**
 * HTML boilerplate.
 */
function html(...$files) {
    global $l;
    global $url;
    global $uid;
    global $bnav;
    global $main;
    global $lang;

    $is_error = $l[2] === null;

    // $preferences = '"';
    // $auth = ['guest'];

    // --- Set headers ---
    $nonce = base64_encode(random_bytes(18));
    header('Content-Type: text/html; charset=utf-8');
    header('Cache-Control: max-age=31536000'); // TODO
    header('Content-Security-Policy: default-src \'none\'; connect-src \'self\'; manifest-src \'self\'; img-src \'self\'; font-src \'self\'; style-src \'self\'; script-src \'nonce-'.$nonce.'\' \'unsafe-inline\' \'unsafe-eval\' https: http:;');
    header('X-XSS-Protection: 1; mode=block');

    // --- Create HTML document ---
    require '../lang/'.$lang.'/include/html.php';

    exit(
    '<!DOCTYPE html>
    <html lang="'.$lang.'">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
    <title>'.$l[0].'</title>
    <meta name="description" content="'.$l[1].'">
    <meta name="keywords" content="aluay'.($is_error ? '' : ','.$l[0].','.$l[2]).'">
    <meta name="theme-color" content="#000000">
    <meta name="referrer" content="no-referrer">
    <meta name="robots" content="'.($is_error ? 'no' : '').'index,follow">
    <meta property="og:title" content="'.$l[0].'">
    <meta property="og:description" content="'.$l[1].'">
    <meta property="og:url" content="https://aluay'.$url.'">
    <meta property="og:image" content="https://aluay/img/open-graph.png">
    <meta property="og:locale" content="'.$lang.'">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="aluay">
    <meta name="twitter:card" content="summary">
    <link rel="canonical" href="https://aluay'.$url.'">'.
    implode('', array_map(function($path) {
        global $nonce;
        // TODO: <link rel="preload" href="/css/'.$path.'.css" as="style" nonce="'.$nonce.'" onload="this.onload=null;this.rel=\'stylesheet\'">
        return '<link rel="stylesheet" href="/css/'.$path.'.css"><script src="/js/'.$path.'.js" defer nonce="'.$nonce.'"></script>';
    }, $files)).
    '<link rel="icon" type="image/svg+xml" href="/img/favicon.svg">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
    <link rel="manifest" href="/manifest.json">
    </head>
    <body>
    <nav>
        <button id="back" data-f="_A" aria-label="'.$L[0].'">
            <img src="/img/apple-touch-icon.png" alt="'.$L[1].'" loading="lazy" width="24">
            <svg viewBox="0 0 8 8"><path d="M8 3.5H1.91L4.71.7 4 0 0 4l4 4 .7-.7-2.79-2.8H8v-1z"/></svg>
            <svg viewBox="0 0 8 8"><path d="M6.35 2.35 8 .71 7.29 0 5.65 1.65 4 3.29 2.35 1.65.71 0 0 .71l1.65 1.64L3.29 4 0 7.29.71 8 4 4.71 7.29 8 8 7.29 4.71 4l1.64-1.65z"/></svg>
        </button>
        <h1>'.$l[0].'</h1>
        <input type="text" placeholder="'.$L[2].'">
        <button data-f="_B" aria-label="'.$L[2].'"><svg viewBox="0 0 8 8"><path d="M8 7.29 5.44 4.73C5.79 4.24 6 3.64 6 3c0-1.66-1.34-3-3-3S0 1.34 0 3s1.34 3 3 3c.65 0 1.24-.21 1.73-.56L7.29 8 8 7.29zM1 3c0-1.1.9-2 2-2s2 .9 2 2a2.012 2.012 0 0 1-.99 1.72C3.71 4.9 3.37 5 3 5c-1.1 0-2-.9-2-2z"/></svg></button>
        <div id="bnav">
            <button data-f="__" data-n="home" aria-label="'.$L[3].'"><svg viewBox="0 0 6 6"><path d="M3 0 0 3v3h2V4h2v2h2V3L3 0z"/></svg></button>
            '.(isset($bnav) ? $bnav :
            '<button data-f="__" data-n="explore" aria-label="'.$L[4].'"><svg viewBox="0 0 8 8"><circle cx="4" cy="4" r=".5"/><path d="M4 0C1.79 0 0 1.79 0 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm1 5L2 6l1-3 3-1-1 3z"/></svg></button>
            <button aria-label="'.$L[5].'"><svg viewBox="0 0 8 8"><path d="M8 3.5H4.5V0h-1v3.5H0v1h3.5V8h1V4.5H8v-1z"/></svg></button>
            <button data-f="__" data-n="inbox" aria-label="'.$L[6].'"><svg viewBox="0 0 8 8"><path d="M0 0v8h8V0H0zm7 5H5.5c0 .83-.67 1.5-1.5 1.5S2.5 5.83 2.5 5H1V1h6v4z"/></svg></button>'
            ).'
            <button data-f="_C" aria-label="'.$L[7].'"><img src="/uc/s/'.$uid.'/0.webp" alt="'.$L[8].'" loading="lazy" width="24"></button>
        </div>
        <div id="popup" class="center hidden">
            <div>
                <h2>'.$L[9].'</h2>
                <p>'.$L[9].'</p>
                <button class="btn red" data-f="_E">'.$L[9].'</button>
                <button class="btn" data-f="_D">'.$L[9].'</button>
            </div>
        </div>
        <noscript class="center">'.$L[10].'</noscript>
        <div id="tmp" class="hidden">
            <span>'.$L[11].'</span>
        </div>
    </nav>
    <div id="side" class="hidden">
        <menu></menu>
        <menu>
            <button><img src="/uc/s/'.$uid.'/0.webp" alt="'.$L[8].'" loading="lazy" width="24"><span>username</span><svg viewBox="0 0 4 8"><path d="M0 3.5 2 0l2 3.5H0zM4 4.5 2 8 0 4.5h4z"/></svg></button>
        </menu>
    </div>
    <div id="main">
        '.$main.'
    </div>
    <footer class="center">
        <span>aluay © 2022</span>
        <button class="a" data-f="__" data-n="privacy-policy">'.$L[12].'</button>
        <button class="a" data-f="__" data-n="terms">'.$L[13].'</button>
    </footer>
    </body>
    </html>'
    );
};
?>
