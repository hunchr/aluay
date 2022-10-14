<?php
/**
 * HTML boilerplate.
 */
function html() {
    global $l;
    global $css;
    global $url;
    global $uid;
    global $bnav;
    global $main;
    global $lang;
    global $og_uri;

    $is_error = $l[2] === null;

    // --- Get preferences ---
    $display = '';
    $auth = ['guest', 0, 1]; // name, show_nsfw, dark_mode

    if (isset($_COOKIE['auth'])) {
        $tmp = explode('|', base64_decode($_COOKIE['auth']), 3);
        count($tmp) === 3 ? $auth = $tmp : $uid = $_SESSION['uid'] = 0;
    }

    if (!$auth[1]) $display .= 'no-nsfw ';
    if (!$auth[2]) $display .= 'light-mode';

    // --- Set headers ---
    $nonce = base64_encode(random_bytes(18));
    header('Cache-Control: max-age=31536000'); // TODO
    header('Content-Security-Policy: default-src \'none\'; connect-src \'self\'; manifest-src \'self\'; img-src \'self\'; font-src \'self\'; style-src \'self\'; script-src \'nonce-'.$nonce.'\' \'unsafe-inline\' \'unsafe-eval\' https: http:;');
    header('X-Content-Type-Options: nosniff');
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
        <meta name="keywords" content="aluay'.($is_error ? '' : ','.$l[0].$l[2]).'">
        <meta name="theme-color" content="#000000">
        <meta name="referrer" content="no-referrer">
        <meta name="robots" content="'.($is_error ? 'no' : '').'index,follow">
        <meta property="og:title" content="'.$l[0].'">
        <meta property="og:description" content="'.$l[1].'">
        <meta property="og:url" content="https://aluay'.$url.'">
        <meta property="og:image" content="https://aluay/'.(isset($og_uri) ? $og_uri : 'img/open-graph').'.webp">
        <meta property="og:image:type" content="image/webp">
        <meta property="og:locale" content="'.$lang.'">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="aluay">
        <meta name="twitter:card" content="summary">
        <link rel="canonical" href="https://aluay'.$url.'">
        <link rel="stylesheet" href="/css/main.css?v=1">'
        .(isset($css) ?
            '<link rel="stylesheet" href="/css/'.$path.'.css?v=1">' :
            '<link rel="stylesheet" href="/css/social.css?v=1">'
        ).
        '<script src="/main.js" defer nonce="'.$nonce.'"></script>
        <link rel="icon" type="image/svg+xml" href="/img/favicon.svg">
        <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
        <link rel="manifest" href="/manifest.json">
    </head>
    <body class="'.$display.'">
    <nav class="btns square">
        <button id="back" data-f="back" aria-label="'.$L[0].'">'
            .svg('aluay').svg('arrow-back').svg('close').
        '</button>
        <h1>'.$l[0].'</h1>
        <input type="text" placeholder="'.$L[1].'">
        <button data-f="search" aria-label="'.$L[2].'">'.svg('search').'</button>
        <div id="bnav">
            <button data-f="get" data-n="home" aria-label="'.$L[3].'">'.svg('home').'</button>'
            .(isset($bnav) ? $bnav :
                '<button data-f="get" data-n="explore" aria-label="'.$L[4].'">'.svg('explore').'</button>
                <button data-f="TODO" aria-label="'.$L[5].'">'.svg('new').'</button>
                <button data-f="get" data-n="inbox" aria-label="'.$L[6].'">'.svg('inbox').'</button>'
            ).
            '<button data-f="side" aria-label="'.$L[7].'">
                <img src="/uc/s/'.$uid.'/0.webp" alt="'.$L[8].'" loading="lazy" width="24">
            </button>
        </div>
        <noscript class="banner center">'.$L[9].'</noscript>
    </nav>
    <div id="side" class="btns hidden">
        <div class="btns">
            <button data-f="get" data-n="profile">'.svg('profile').'<span>Profile</span></button>
            <button data-f="get" data-n="communities">'.svg('community').'<span>Communities</span></button>
            <button data-f="get" data-n="lists">'.svg('list').'<span>Lists</span></button>
            <button data-f="get" data-n="saved">'.svg('save').'<span>Saved</span></button>
            <button data-f="get" data-n="liked">'.svg('like').'<span>Liked</span>'.svg('expand-right').'</button>
            <button data-f="get" data-n="settings">'.svg('settings').'<span>Settings</span></button>
            <button data-f="get" data-n="premium">'.svg('premium').'<span>Premium</span></button>
            <button data-f="get" data-n="help/aluay">'.svg('help').'<span>Help</span></button>
            <button data-f="get" data-n="feedback/aluay">'.svg('feedback').'<span>Feedback</span></button>
            <button data-f="get" data-n="privacy-policy">'.svg('policy').'<span>Privacy Policy</span></button>
            <button data-f="get" data-n="terms">'.svg('terms').'<span>Terms</span></button>
            <button data-f="get" data-n="apps">'.svg('apps').'<span>More Apps</span></button>
        </div>
        <button>
            <img src="/uc/s/'.$uid.'/0.webp" alt="'.$L[8].'" loading="lazy" width="24">
            <span>'.htmlspecialchars($auth[0]).'</span>
            '.svg('expand').'
        </button>
    </div>
    <div id="main">
        '.$main.'
    </div>
    <div class="hidden">
        <span>'.$L[10].'</span>
        <svg viewBox="0 0 8 8">
            <g id="i-aluay"><rect width="8" height="8" rx="2.67" ry="2.67" fill="#6060ff"/><path d="M6.58 2.96c-.48-.13-.99-.11-1.47.04C4.88 2.46 4.5 2 4 1.71 3.5 2 3.12 2.46 2.89 3a2.82 2.82 0 0 0-1.47-.04c-.23.89-.01 1.87.68 2.57.52.52 1.21.78 1.89.77.69 0 1.37-.25 1.89-.77.7-.7.92-1.68.68-2.57ZM3.17 4.97a.67.67 0 1 1 0-1.34.67.67 0 0 1 0 1.34Zm1.67 0a.67.67 0 1 1 0-1.34.67.67 0 0 1 0 1.34Z" fill="#fff"/></g>
            <path id="i-arrow-back" d="M8 3.5H1.92L4.71.71 4 0 0 4l4 4 .7-.7-2.78-2.8h6.09v-1Z"/>
            <path id="i-close" d="M6.36 2.36 8 .71 7.3 0 5.65 1.65 4 3.3 2.36 1.65.71 0 0 .71l1.65 1.65L3.3 4 0 7.3l.71.7L4 4.71 7.3 8l.7-.7L4.71 4l1.65-1.64z"/>
            <path id="i-search" d="M8 7.3 5.44 4.74c.35-.49.56-1.09.56-1.73C6 1.35 4.66 0 3 0S0 1.35 0 3s1.34 3 3 3c.65 0 1.24-.21 1.73-.56L7.29 8 8 7.29ZM1 3c0-1.1.9-2 2-2s2 .9 2 2a2.012 2.012 0 0 1-.99 1.72C3.71 4.9 3.37 5 3 5c-1.1 0-2-.9-2-2Z"/>
            <path id="i-home" d="M4 0 0 4v4h3V5h2v3h3V4L4 0z"/>
            <g id="i-explore"><circle cx="4" cy="4" r=".5"/><path d="M4 0C1.79 0 0 1.79 0 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4Zm1 5L2 6l1-3 3-1-1 3Z"/></g>
            <path id="i-new" d="M8 3.5H4.5V0h-1v3.5H0v1h3.5V8h1V4.5H8v-1z"/>
            <path id="i-inbox" d="M0 0v8h8V0H0Zm7 5H5.5c0 .8-.7 1.5-1.5 1.5S2.5 5.8 2.5 5H1V1h6v4Z"/>
            <path id="i-notifications" d="M5 7c0 .55-.45 1-1 1s-1-.45-1-1M6.5 5.5V3.25c0-1.12-.74-2.05-1.75-2.37V.75a.749.749 0 1 0-1.5 0v.13A2.497 2.497 0 0 0 1.5 3.25V5.5L.5 6v.5h7V6l-1-.5Z"/>
            <g id="i-profile"><circle cx="4" cy="2" r="2"/><path d="M0 8c0-2.2 1.8-4 4-4s4 1.8 4 4"/></g>
            <g id="i-community"><path d="M5 .5c-.18 0-.34.04-.5.09.58.21 1 .76 1 1.41s-.42 1.2-1 1.41c.16.06.32.09.5.09.83 0 1.5-.67 1.5-1.5S5.83.5 5 .5Z"/><circle cx="3" cy="2" r="1.5"/><path d="M3 3.5c-1.65 0-3 1.35-3 3v1h6v-1c0-1.66-1.34-3-3-3Z"/><path d="M5 3.5c-.17 0-.34.02-.5.05A2.99 2.99 0 0 1 7 6.5v1h1v-1c0-1.66-1.34-3-3-3Z"/></g>
            <g id="i-list"><path d="M0 0v6l2-1h4V0H0ZM8 6v2L6 7H2V6h6Z"/><path d="M7 2h1v4.1H7z"/></g>
            <path id="i-settings" d="M8 3H6.41l1.12-1.12L6.12.47 5 1.59V0H3v1.59L1.88.47.47 1.88 1.59 3H0v2h1.59L.47 6.12l1.41 1.41L3 6.41V8h2V6.41l1.12 1.12 1.41-1.41L6.41 5H8V3ZM4 5.25a1.25 1.25 0 1 1 0-2.5 1.25 1.25 0 0 1 0 2.5Z"/>
            <path id="i-help" d="M4 0C1.79 0 0 1.79 0 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4Zm.5 7h-1V6h1v1Zm0-1.5h-1C3.5 3.88 5 4 5 3c0-.55-.45-1-1-1s-1 .45-1 1H2c0-1.1.9-2 2-2s2 .9 2 2c0 1.25-1.5 1.38-1.5 2.5Z"/>
            <path id="i-feedback" d="M.75 0v6.5H2.5L4 8l1.5-1.5h1.75V0H.75Zm3.96 3.96L4 5.25l-.71-1.29L2 3.25l1.29-.71L4 1.25l.71 1.29L6 3.25l-1.29.71Z"/>
            <g id="i-policy"><path d="M4 0 .5 1.5v1S.5 7 4 8c.87-.25 1.53-.72 2.02-1.28L5.01 5.71c-.3.18-.64.28-1.01.28-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2c0 .37-.11.71-.28 1.01l.88.88c.9-1.58.9-3.39.9-3.39V1.5L4 0Z"/><circle cx="4" cy="4" r="1"/></g>
            <path id="i-terms" d="m.004 4.18 1.393-1.394 2.086 2.086L2.09 6.265zM2.784 1.398 4.177.005 6.263 2.09 4.87 3.484zM1.742 2.438l.693-.693L8 7.31l-.693.692z"/>
            <path id="i-apps" d="M0 6h2v2H0zM0 0h2v2H0zM0 3h2v2H0zM6 6h2v2H6zM6 0h2v2H6zM6 3h2v2H6zM3 6h2v2H3zM3 0h2v2H3zM3 3h2v2H3z"/>
            <path id="i-expand-right" d="M1.65.71 4.94 4 1.65 7.3l.71.71 4-4-4-4.01-.71.71Z"/>
            <path id="i-expand-left" d="M6.36.71 3.07 4l3.29 3.29-.71.71-4-4 4-4 .71.71Z"/>
            <path id="i-expand" d="m2.42 5-.71.71L4 8l2.29-2.29L5.58 5 3.99 6.59 2.4 5ZM2.42 3l-.71-.71L4 0l2.29 2.29-.71.71-1.59-1.59L2.4 3Z"/>
            <path id="i-like" d="m4 7.75 3.56-3.93c.67-.91.56-2.2-.29-2.98C6.36 0 4.93.07 4.09.99c-.04.04-.05.09-.08.13-.03-.04-.06-.09-.09-.13A2.26 2.26 0 0 0 .73.84c-.85.78-.96 2.06-.29 2.98"/>
            <path id="i-post" d="M0 0v8l3-2h5V0H0Z"/>
            <path id="i-save" d="m4 6.51-2.47 1.3L2 5.05 0 3.1l2.77-.4L4 .2l1.24 2.5L8 3.1 6 5.05l.48 2.76L4 6.51z"/>
            <path id="i-reply" d="M7 4.75c-1.36-1.94-3.47-2.4-4-2.5V.75l-3 3 3 3v-1.5c.55-.05 1.71-.09 3 .5 1.01.46 1.66 1.1 2 1.5-.09-.6-.33-1.55-1-2.5Z"/>
            <path id="i-error" d="M4 0C1.79 0 0 1.79 0 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4Zm.5 6.75h-1v-1h1v1Zm0-2h-1v-3.5h1v3.5Z"/>
            <path id="i-edit" d="m5.876.709.707-.707 1.415 1.414-.708.707zM5.53 1.06 0 6.59V8h1.42l5.52-5.52-1.41-1.42z"/>
            <path id="i-premium" d="m4.5 8 2-2V4.5L4 6.5c.17.5.33 1 .5 1.5ZM3.5 1.5H2l-2 2c.5.17 1 .33 1.5.5l2-2.5ZM2.64 5.37a1.25 1.25 0 0 0-1.77 0c-.04.04-.08.09-.11.13C.13 6.27-.01 7.22 0 8c.78.01 1.73-.12 2.5-.75.05-.04.09-.08.13-.11.49-.49.49-1.28 0-1.77ZM6 .5l-2 1L2 4l2 2 2.5-2 1-2L8 0 6 .5Zm-.5 2.75c-.41 0-.75-.34-.75-.75s.34-.75.75-.75.75.34.75.75-.34.75-.75.75Z"/>
            <path id="i-verified" d="m8 2.35-1.7-.64L5.66 0 4 .75 2.35 0l-.64 1.7L0 2.35l.75 1.66L0 5.66l1.7.64.64 1.7L4 7.25 5.66 8l.64-1.7L8 5.66 7.25 4 8 2.34ZM3.3 5.77 1.89 4.36l.71-.71.71.71 2.12-2.12.71.71-2.83 2.83Z"/>
            <g id="i-nsfw"><path d="M4.5 4.5h1v1h-1zM4.5 2.5h1v1h-1z"/><path d="M0 0v8h8V0H0Zm2.5 6.5h-1v-5h1v5Zm4 0h-3v-5h3v5Z"/></g>
            <path id="i-sparkle" d="M6.95 6.06 6.5 5l-.44 1.06L5 6.5l1.06.44L6.5 8l.45-1.06L8 6.5l-1.05-.44zM6 3l.45-1.06L7.5 1.5l-1.05-.44L6 0l-.44 1.06-1.06.44 1.06.44L6 3zM3.56 3.19l-.81-1.94-.81 1.94L0 4l1.94.81.81 1.94.81-1.94L5.5 4l-1.94-.81z"/>
            <g id="i-created"><path d="M6.5 1V0h-1v1h-3V0h-1v1H0v7h8V1M1 7V2.5h6V7H1Z"/><path d="M2 3.5h4v1H2zM2 5h3v1H2z"/></g>
            <g id="i-more-h"><circle cx="1" cy="4" r="1"/><circle cx="7" cy="4" r="1"/><circle cx="4" cy="4" r="1"/></g>
            <g id="i-interests"><circle cx="1.75" cy="6.25" r="1.75"/><path d="M4.5 4.5H8V8H4.5zM2 0 0 3.5h4L2 0zM7.64.22a.97.97 0 0 0-1.37.15v.01-.01C5.93-.05 5.32-.12 4.9.22s-.49.96-.15 1.38l.28.35L6.27 3.5h.02l1.24-1.55.28-.35A.984.984 0 0 0 7.66.22Z"/></g>
            <g id="i-upload"><path d="M7 3 4 0 1 3l.71.71L3.5 1.92V6h1V1.92l1.8 1.79L7 3z"/><path d="M7 5v2H1V5.01H0V8h8V5H7Z"/></g>
            <path id="i-poll" d="M0 3h2v5H0zM6 4h2v4H6zM3 1h2v7H3z"/>
            <path id="i-play" d="m7.5 4-7-4v8l7-4z"/>
            <path id="i-replay" d="M4 1.5V0L2 2l2 2V2.5c1.2 0 2.2 1 2.2 2.2S5.2 6.9 4 6.9s-2.2-1-2.2-2.2h-1C.8 6.5 2.3 7.9 4 7.9s3.2-1.5 3.2-3.2S5.7 1.5 4 1.5Z"/>
            <path id="i-forward" d="M4 1.5V0l2 2-2 2V2.5c-1.2 0-2.2 1-2.3 2.2S2.7 7 3.9 7s2.3-1 2.3-2.2h1C7.2 6.6 5.7 8 3.9 8S.8 6.5.8 4.8 2.2 1.5 4 1.5Z"/>
            <g id="i-volume-up"><path d="M5 0v1c1.16.41 2 1.7 2 3s-.84 2.59-2 3v1c1.72-.45 3-2.14 3-4S6.73.45 5 0Z"/><path d="M6 4c0-.74-.4-1.65-1-2v4c.6-.35 1-1.26 1-2ZM0 2.5v3h2l2 2v-7l-2 2H0z"/></g>
            <g id="i-speed"><path d="m4.93 4.89 1.83-3.14-3.14 1.83a.998.998 0 0 0 .39 1.92c.42 0 .77-.25.92-.61Z"/><path d="m7.15 2.06-.71.71c.35.49.56 1.09.56 1.73 0 .77-.3 1.47-.78 2H1.78C1.3 5.97 1 5.27 1 4.5c0-1.66 1.34-3 3-3 .65 0 1.24.21 1.73.56l.71-.71C5.76.82 4.92.5 4 .5c-2.21 0-4 1.79-4 4 0 1.2.54 2.27 1.38 3h5.24C7.46 6.77 8 5.7 8 4.5c0-.92-.33-1.76-.85-2.44Z"/></g>
            <path id="i-fullscreen" d="M3 0H0v3h1V1h2V0ZM5 0h3v3H7V1H5V0ZM3 8H0V5h1v2h2v1ZM5 8h3V5H7v2H5v1Z"/>
            <path id="i-fullscreen-exit" d="M3 0v3H0V2h2V0h1ZM5 0v3h3V2H6V0H5ZM3 8V5H0v1h2v2h1ZM5 8V5h3v1H6v2H5Z"/>
        </svg>
    </div>
    </body>
    </html>'
    );
};
?>
