<?php
/**
 * Shows a specific user profile.
 * @param array $q username
 */
require '../include/sql/social-query.php';

// Profile
query(
    'SELECT s.*, UNIX_TIMESTAMP(s.created) AS created, u.badges, u.language, '
    .($uid ?
        'IF ((SELECT uid FROM liked_subs l WHERE l.uid = '.$uid.' AND l.sid = s.id), TRUE, FALSE) AS liked ':
        'NULL AS liked '
    ).
    'FROM users u
    INNER JOIN subs s
    ON u.id = s.id
    WHERE u.uname = "'.$q[0].'"',
    function() {
        global $uri; // TODO: change to lang
        require '../include/error.php';
    },
    function($data) {
        global $q;
        global $l;
        global $main;

        $l[0] = $q[0];
        $l[1] = substr($data['description'], 0, 100); // TODO: test

        $main = 
        '<main class="vis" data-title="'.$l[0].'">
        <div class="profile">
            <div class="profile-t">
                <img src="/uc/s/'.$data['id'].'/0.webp" alt="'.$l[3].'" loading="lazy" width="40">
                <div>
                    <span>'.$data['name'].'</span>
                    <span>@'.$l[0].'</span>
                </div>
                <button data-f="pA" class="btn'.($data['liked'] ? ' liked">'.$l[4].'' : '">'.$l[5].'').'</button>
            </div>
            <p>'.fstring($data['description']).'</p>
            <div>
                <svg viewBox="0 0 5.5 4"><circle cx="2" cy="1" r="1"/><path d="M0 4c0-1.1.9-2 2-2s2 .9 2 2M3.5 0c-.19 0-.35.06-.5.15.29.17.5.48.5.85s-.21.67-.5.85c.15.09.31.15.5.15.55 0 1-.45 1-1s-.45-1-1-1z"/><path d="M3.5 2c-.17 0-.34.03-.5.07.86.22 1.5 1 1.5 1.93h1c0-1.1-.9-2-2-2z"/></svg>
                <span aria-label="'.$l[6].'">'.fnumber($data['likes']).'</span>
                <svg viewBox="0 0 7.5 7.5"><path d="M0 0v7.5L2.5 6h5V0H0z"/></svg>
                <span aria-label="'.$l[7].'">'.fnumber($data['posts']).'</span>
                <svg viewBox="0 0 8 8"><path d="M6.5 1V0h-1v1h-3V0h-1v1H0v7h8V1M1 7V2.5h6V7H1z"/><path d="M2 3.5h4v1H2zM2 5h3v1H2z"/></svg>
                <span aria-label="'.$l[8].'">'.fdate($data['created']).'</span>
                <button data-f="pB" aria-label="'.$l[9].'"><svg viewBox="0 0 8 2"><circle cx="1" cy="1" r="1"/><circle cx="7" cy="1" r="1"/><circle cx="4" cy="1" r="1"/></svg></button></button>
            </div>
        </div>
        ';
    }
);

$conn -> close();
$main .= '</main>';

// Generate HTML
$is_fetch ? exit($main) : require '../include/html.php';
html('main', 'social');
?>
