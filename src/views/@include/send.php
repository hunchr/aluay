<?php
/**
 * Sends the HTML page to the client.
 */
// Set headers
$nonce = base64_encode(random_bytes(18));
header('Cache-Control: max-age=31536000'); // TODO
header('Content-Security-Policy: default-src \'none\'; connect-src \'self\'; manifest-src \'self\'; img-src \'self\'; font-src \'self\'; style-src \'self\'; script-src \'nonce-'.$nonce.'\' \'unsafe-inline\' \'unsafe-eval\' https: http:;');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');

// Get variables
global $css;
global $sidebar;
global $og_image;

$auth = ['guest'];

include '../../locales/'.$lang.'/@include/send.php';

exit(
'<!DOCTYPE html>
<html lang="'.$lang.'">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
    <title>'.$l['title'].'</title>
    <meta name="description" content="'.$l['description'].'">
    <meta name="keywords" content="aluay,'.$l['keywords'].'">
    <meta name="theme-color" content="#000000">
    <meta name="referrer" content="no-referrer">
    <meta name="robots" content="'.($l['keywords'] === null ? 'no' : '').'index,follow">
    <meta property="og:title" content="'.$l['title'].'">
    <meta property="og:description" content="'.$l['description'].'">
    <meta property="og:url" content="http://[::1]'.$url.'">
    <meta property="og:image" content="http://[::1]/'.(isset($og_image) ? $og_image : 'img/open-graph').'.webp">
    <meta property="og:image:type" content="image/webp">
    <meta property="og:locale" content="'.$lang.'">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="aluay">
    <meta name="twitter:card" content="summary">
    <link rel="canonical" href="http://[::1]'.$url.'">
    <link rel="stylesheet" href="/css/_main.css">
    <link rel="stylesheet" href="/css/'.(isset($css) ? $css : 'social').'.css">
    <script src="/js/_main.js" nonce="'.$nonce.'" defer></script>
    <link rel="icon" type="image/svg+xml" href="/img/favicon.svg">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
    <link rel="manifest" href="/manifest.json">
</head>
<body>
<nav id="nav" class="li sq banner">
    <button data-f="nav.s" aria-label="'.$L['sidebar_aria'].'">'.icon('menu').'</button>
    <button id="back" data-f="nav.b" aria-label="'.$L['back_aria'].'">
        <img src="/img/favicon.svg" alt="'.$L['logo_alt'].'" width="24">
        '.icon('arrow_back').'
    </button>
    <h1>'.$l['title'].'</h1>
    <div class="input">
        '.icon('search').'
        <input type="text" placeholder="'.$L['search_ph'].'">
        <button data-f="search.a" aria-label="'.$L['search_advanced_aria'].'">'.icon('tune').'</button>
    </div>
    <button data-f="search.s" aria-label="'.$L['search_aria'].'">'.icon('search').'</button>
    <div id="bnav">
        <button data-f="get.i" aria-label="'.$L['home_aria'].'">'.icon('home').'</button>
        <button data-f="get" data-n="community" aria-label="'.$L['community_aria'].'">'.icon('group').'</button>
        <button aria-label="'.$L['new_aria'].'">'.icon('add').'</button>
        <button data-f="get.i" aria-label="'.$L['explore_aria'].'">'.icon('explore').'</button>
        <button data-f="get.i" aria-label="'.$L['inbox_aria'].'">'.icon('inbox').'</button>
    </div>
    <button data-f="nav.a" aria-label="'.$L['acc_aria'].'">
        <img src="/uc/s/'.$uid.'/0.webp" alt="'.$L['pfp_alt'].'" width="24">
    </button>
    <div id="acc" class="li menu hidden">
        <button data-f="get" data-n="@'.$auth[0].'">'.icon('person').'<span>'.$L['profile_btn'].'</span></button>
        <button data-f="get" data-n="add-account">'.icon('person_add').'<span>'.$L['add_acc_btn'].'</span></button>
        <button data-f="get" data-n="change-account">'.icon('swap_horiz').'<span>'.$L['change_acc_btn'].'</span></button>
        <span></span>
        <button data-f="get.i">'.icon('settings').'<span>'.$L['settings_btn'].'</span></button>
        <button data-f="get" data-n="premium">'.icon('rocket_launch').'<span>'.$L['premium_btn'].'</span></button>
        <button data-f="get" data-n="help">'.icon('contact_support').'<span>'.$L['help_btn'].'</span></button>
        <button data-f="get.i">'.icon('policy').'<span>'.$L['policy_btn'].'</span></button>
        <button data-f="get" data-n="terms">'.icon('gavel').'<span>'.$L['terms_btn'].'</span></button>
        <span></span>
        <button data-f="get.i">'.icon('apps').'<span>'.$L['apps_btn'].'</span></button>
        <span></span>
        <button data-f="get.i">'.($uid ?
            icon('logout').'<span>'.$L['logout_btn'] :
            icon('login').'<span>'.$L['login_btn']
        ).'</span></button>
    </div>
    <noscript class="banner">'.$L['noscript_note'].'</noscript>
</nav>
<div id="side" data-f="side">
    <div class="li">'.($sidebar ? $sidebar :
        '<button>'.icon('group').'<span>Communities</span></button>
        <button>'.icon('library_books').'<span>Lists</span></button>
        <button>'.icon('star').'<span>Saved</span></button>
        <button>'.icon('favorite').'<span>Liked</span>'.icon('chevron_right').'</button>'
    ).'</div>
</div>
<div id="main">'.$html.'</div>
</body>
</html>'
);
?>
