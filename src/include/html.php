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
            // TODO: <link rel="preload" href="/css/'.$path.'.css" as="style"
                // nonce="'.$nonce.'" onload="this.onload=null;this.rel=\'stylesheet\'">
            return
                '<link rel="stylesheet" href="/css/'.$path.'.css">
                <script src="/js/'.$path.'.js" defer nonce="'.$nonce.'"></script>';
        }, $files)).
        '<link rel="icon" type="image/svg+xml" href="/img/favicon.svg">
        <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
        <link rel="manifest" href="/manifest.json">
    </head>
    <body>
    <nav>
        <button id="back" data-f="_A" aria-label="'.$L[0].'">
            <img src="/img/apple-touch-icon.png" alt="'.$L[1].'" loading="lazy" width="24">
            '.svg('arrow-back').'
            '.svg('close').'
        </button>
        <h1>'.$l[0].'</h1>
        <input type="text" placeholder="'.$L[2].'">
        <button data-f="_B" aria-label="'.$L[2].'">'.svg('search').'</button>
        <div id="bnav">
            <button data-f="__" data-n="home" aria-label="'.$L[3].'">'.svg('home').'</button>
            '.(isset($bnav) ? $bnav :
            '<button data-f="__" data-n="explore" aria-label="'.$L[4].'">'.svg('explore').'</button>
            <button aria-label="'.$L[5].'">'.svg('new').'</button>
            <button data-f="__" data-n="inbox" aria-label="'.$L[6].'">'.svg('inbox').'</button>'
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
    </nav>
    <div id="side" class="hidden">
        <menu></menu>
        <menu>
            <button>
                <img src="/uc/s/'.$uid.'/0.webp" alt="'.$L[8].'" loading="lazy" width="24">
                <span>username</span>
                '.svg('expand').'
            </button>
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
    <div id="tmp" class="hidden">
        <span>'.$L[11].'</span>
        <svg viewBox="0 0 8 8">
            <path id="svg-arrow-back" d="M8 3.5H1.9L4.7.7 4 0 0 4l4 4 .7-.7-2.8-2.8H8v-1Z"/>
            <path id="svg-close" d="M6.4 2.4 8 .7 7.3 0 5.6 1.6 4 3.3 2.4 1.6.7 0 0 .7l1.6 1.7L3.3 4 0 7.3l.7.7L4 4.7 7.3 8l.7-.7L4.7 4l1.7-1.6z"/>
            <path id="svg-search" d="M8 7.3 5.4 4.7c.3-.5.6-1.1.6-1.7 0-1.7-1.3-3-3-3S0 1.3 0 3s1.3 3 3 3 1.2-.2 1.7-.6L7.3 8l.7-.7ZM1 3c0-1.1.9-2 2-2s2 .9 2 2-.1.7-.3 1c-.2.3-.4.5-.7.7-.3.2-.6.3-1 .3-1.1 0-2-.9-2-2Z"/>
            <path id="svg-home" d="M4 0 0 4v4h3V5h2v3h3V4L4 0z"/>
            <g id="svg-explore"><circle cx="4" cy="4" r=".5"/><path d="M4 0C1.8 0 0 1.8 0 4s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4Zm1 5L2 6l1-3 3-1-1 3Z"/></g>
            <path id="svg-new" d="M8 3.5H4.5V0h-1v3.5H0v1h3.5V8h1V4.5H8v-1z"/>
            <path id="svg-inbox" d="M0 0v8h8V0H0Zm7 5H5.5c0 .8-.7 1.5-1.5 1.5S2.5 5.8 2.5 5H1V1h6v4Z"/>
            <path id="svg-notifications" d="M5 7c0 .6-.4 1-1 1s-1-.4-1-1M6.5 5.5V3.3c0-1.1-.7-2.1-1.8-2.4C4.8.3 4.4 0 4 0s-.8.3-.8.8c-1 .4-1.8 1.4-1.8 2.5v2.2L.4 6v.5h7.1V6l-1-.5Z"/>
            <g id="svg-profile"><circle cx="4" cy="2" r="2"/><path d="M0 8c0-2.2 1.8-4 4-4s4 1.8 4 4"/></g>
            <g id="svg-community"><path d="M6.5 2.5C6.5 1.7 5.8 1 5 1s-.4 0-.6.1c.5.2.9.8.9 1.4s-.4 1.1-.9 1.4c.2 0 .4.1.6.1.8 0 1.5-.7 1.5-1.5Z"/><path d="M5 4h-.6c1.4.3 2.4 1.5 2.4 2.9H8c0-1.7-1.3-3-3-3Z"/><circle cx="3" cy="2.5" r="1.5"/><path d="M3 4C1.3 4 0 5.3 0 7h6c0-1.7-1.3-3-3-3Z"/></g>
            <path id="svg-list" d="M0 0v6.5l2.4-1.6h4.1V0H0Z"/><path d="M7.3 1.5v4.1H3.2l-1.1.8h3.5L8 8V1.5h-.8Z"/>
            <path id="svg-apps" d="M0 6h2v2H0zM0 0h2v2H0zM0 3h2v2H0zM6 6h2v2H6zM6 0h2v2H6zM6 3h2v2H6zM3 6h2v2H3zM3 0h2v2H3zM3 3h2v2H3z"/>
            <path id="svg-terms" d="M-.013 4.156 1.401 2.74l2.122 2.122-1.415 1.414zM2.687 1.437 4.101.023l2.122 2.121L4.808 3.56zM1.748 2.425l.707-.707L8.04 7.304l-.707.707z"/>
            <path id="svg-expand-right" d="M1.6.7 4.9 4 1.6 7.3l.7.7 4-4-3.9-4-.7.7Z"/>
            <path id="svg-expand" d="m2.4 5-.7.7L4 8l2.3-2.3-.7-.7L4 6.6 2.4 5ZM2.4 3l-.7-.7L4 0l2.3 2.3-.7.7L4 1.4 2.4 3Z"/>
            <path id="svg-contract" d="m2.4 8-.7-.7L4 5l2.3 2.3-.7.7L4 6.4 2.4 8ZM2.4 0l-.7.7L4 3 6.3.7 5.6 0 4 1.6 2.4 0Z"/>
            <path id="svg-like" d="m4 7.8 3.6-3.9c.7-.9.6-2.2-.3-3C6.4 0 4.9 0 4.1 1v.1V1C3.1 0 1.7 0 .7.8c-.8.8-.9 2.1-.3 3"/>
            <path id="svg-post" d="M0 0v8l3-2h5V0H0Z"/>
            <path id="svg-save" d="M4 6.5 1.5 7.8 2 5.1l-2-2 2.8-.4L4 .2l1.2 2.5 2.8.4-2 2 .5 2.7L4 6.5z"/>
            <path id="svg-reply" d="M7 4.8C5.6 2.9 3.5 2.4 3 2.3V.8l-3 3 3 3V5.3c.5 0 1.7 0 3 .5 1 .5 1.7 1.1 2 1.5 0-.6-.3-1.5-1-2.5Z"/>
            <path id="svg-error" d="M4 0C1.8 0 0 1.8 0 4s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4Zm.5 6.8h-1v-1h1v1Zm0-2h-1V1.3h1v3.5Z"/>
            <g id="svg-nsfw"><path d="M4.5 4.5h1v1h-1zM4.5 2.5h1v1h-1z"/><path d="M0 0v8h8V0H0Zm2.5 6.5h-1v-5h1v5Zm4 0h-3v-5h3v5Z"/></g>
            <g id="svg-created"><path d="M6.5 1V0h-1v1h-3V0h-1v1H0v7h8V1M1 7V2.5h6V7H1Z"/><path d="M2 3.5h4v1H2zM2 5h3v1H2z"/></g>
            <g id="svg-more-h"><circle cx="1" cy="4" r="1"/><circle cx="7" cy="4" r="1"/><circle cx="4" cy="4" r="1"/></g>
        </svg>
    </div>
    </body>
    </html>'
    );
};
?>
