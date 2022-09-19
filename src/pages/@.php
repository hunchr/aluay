<?php
/**
 * Shows a specific user profile.
 * @param array $q username
 */
require '../include/sql/social-query.php';

// ----- Profile -----
if (query(
    'SELECT s.*, UNIX_TIMESTAMP(s.created) AS created, u.badges, u.language,'
    .($uid ?
        'IF ((SELECT uid FROM liked_subs l WHERE l.uid = '.$uid.' AND l.sid = s.id), TRUE, FALSE) AS liked ':
        'NULL AS liked '
    ).
    'FROM users u
    INNER JOIN subs s
    ON u.id = s.id
    WHERE u.uname = "'.$q[0].'";',
    function($data) {
        global $q;
        global $l;
        global $main;
        global $liked_type;

        $liked_type = [$data['liked'], $data['type']];
        
        // Archived/Banned
        if ($liked_type[1] > 2) {
            $l[1] = $liked_type[1] === 3 ? $l[17] : $l[18];
            require '../include/error.php';
        }
        // Public/Unlisted/Private // TODO: badges
        else {
            $l[0] = $q[0];
            $l[1] = substr($data['description'], 0, 100); // TODO: test
            $l[2] = 'user';
            $main =
            '<main class="vis" data-title="'.$l[0].'">
            <div class="profile">
                <div class="profile-t">
                    <img src="/uc/s/'.$data['id'].'/0.webp" alt="'.$l[3].'" loading="lazy" width="40">
                    <div>
                        <span>'.$data['name'].'</span>
                        <span>@'.$l[0].'</span>
                    </div>
                    <button data-f="pA" class="btn'.($liked_type[0] ? ' liked">'.$l[4].'' : '">'.$l[5].'').'</button>
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

    }
) === 0) {
    require '../include/error.php';
}

// ----- Posts -----
// Private
if ($liked_type[1] === 2 && !$liked_type[0]) {
    $main .=
    '<span class="end center">
        '.$l[16].'
    </span>';
}
// Public/Unlisted
else {
    $rows = query(
        'SELECT p.*, UNIX_TIMESTAMP(p.created) AS created,
        IF (p.pid, CONCAT("&", (SELECT c.cname FROM communities c WHERE c.id = p.pid)), "@'.$q[0].'") as name,'
        .($uid ?
            'IF ((SELECT uid FROM liked_posts l WHERE l.uid = '.$uid.' AND l.pid = p.id), " class=\"liked\"", NULL) AS liked ':
            'NULL AS liked '
        ).
        'FROM posts p
        WHERE p.uid = (SELECT id FROM users u WHERE u.uname = "'.$q[0].'")
        AND NOT p.category = 0
        ORDER BY p.created DESC
        LIMIT 5;',
        function($data) {
            global $l;
            global $main;
    
            $main .=
            '<article class="post">
                <div>
                    <button data-f="pa">'.$data['name'].'</button>&nbsp;•&nbsp;
                    <button data-f="pb" data-unix="'.$data['created'].'">'.fdate($data['created']).'</button>
                    <button data-f="pc" aria-label="'.$l[9].'"><svg viewBox="0 0 8 2"><circle cx="1" cy="1" r="1"/><circle cx="7" cy="1" r="1"/><circle cx="4" cy="1" r="1"/></svg></button>
                </div>
                <p>'.fstring($data['description']).'</p>
                <div data-id="'.$data['id'].'">
                    <button'.$data['liked'].' data-f="pd" aria-label="'.$l[10].'"><svg viewBox="0 0 8 7.5"><path d="m4 7.5 3.56-3.93c.67-.91.56-2.2-.29-2.98-.92-.84-2.34-.77-3.18.14-.04.04-.05.09-.08.13-.03-.04-.06-.09-.09-.13C3.07-.18 1.65-.25.73.59c-.86.78-.96 2.06-.3 2.98"/></svg>'.fnumber($data['likes']).'</button>
                    <button data-f="pe" aria-label="'.$l[11].'"><svg viewBox="0 0 7.5 7.5"><path d="M0 0v7.5L2.5 6h5V0H0z"/></svg>'.fnumber($data['replies']).'</button>
                    <button data-f="pf"><svg viewBox="0 0 8 7.61"><path d="m4 6.31-2.47 1.3L2 4.86 0 2.91l2.76-.41L4 0l1.24 2.5L8 2.91 6 4.86l.47 2.75L4 6.31z"/></svg>'.$l[12].'</button>
                    <button data-f="pg"><svg viewBox="0 0 8 6.5"><path d="M7 4C5.64 2.06 3.53 1.6 3 1.5V0L0 3l3 3V4.5c.55-.05 1.71-.09 3 .5 1.01.46 1.66 1.1 2 1.5-.09-.6-.33-1.55-1-2.5z"/></svg>'.$l[13].'</button>    
                </div>
            </article>';
    
            // TODO:
            // <div class="media">
            //     <img src="../public/uc/s/0/0.webp" alt="Image" loading="lazy" width="540">
            // </div>
        }
    );
    
    $conn -> close();
    
    if ($rows < 5) {
        $main .=
        '<span class="end center">
            '.($rows === 0 ? $l[14] : $l[15]).'
        </span>';
    }
}

$main .= '</main>';

// ----- Generate HTML -----
$is_fetch ? exit($main) : require '../include/html.php';
html('main', 'social');
?>
