<?php
/**
 * Shows a specific user profile.
 * @param array $q username
 */
require '../include/sql-social.php';

// ----- Profile -----
if (squery(
    'SELECT s.*, UNIX_TIMESTAMP(s.created) AS created,'
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
        global $id;
        global $cat;
        global $main;
        global $og_uri;
        global $is_liked;

        $id = $data['id'];
        $og_uri = 'uc/s/'.$data['id'].'/0';
        $is_liked = $data['liked'];
        [$badges, $cat] = str_split(str_pad($data['info'], 2, '0', 0));
        $badges = implode('', array_map(function($badge) {
            return svg($badge);
        }, [
            [],
            ['mod'],
            ['bot'],
            ['verified'],
            ['premium'],
            ['verified','premium'],
            ['nsfw'],
            ['verified','nsfw'],
            ['premium','nsfw'],
            ['verified','premium','nsfw'],
        ][$badges]));

        // Hidden profile
        if ($cat > 2) {
            // Moved
            if ($cat === 4) {
                exit('todo: moved to @'.$data['name']);
            }

            // Archived/Banned
            $l[1] = $cat === 3 ? $l[17] : $l[18];
            require '../include/error.php';
        }
        // Public profile
        else {
            $l[0] = $q[0];
            $l[1] = strlen($data['description']) > 149 ?
                substr($data['description'], 0, 149).'…' :
                $data['description'];
            $l[2] = 'user';
            $main =
            '<main class="vis" data-title="'.$l[0].'" data-url="'.$l[0].'">
            <div class="profile">
                <div class="profile-t">
                    <img src="/'.$og_uri.'.webp" alt="'.$l[3].'" loading="lazy" width="40">
                    <div>
                        <div>
                            <span>'.$data['name'].'</span>
                            '.$badges.'
                        </div>
                        <div>
                            <span>@'.$l[0].'</span>
                        </div>
                    </div>
                    <button data-f="sa" class="blue fit min'.($is_liked ? ' liked">'.$l[4] : '">'.$l[5]).'</button>
                </div>
                <p>'.fstring($data['description']).'</p>
                <div>
                    '.svg('community').'
                    <span aria-label="'.$l[6].'">'.fnumber($data['likes']).'</span>
                    '.svg('post').'
                    <span aria-label="'.$l[7].'">'.fnumber($data['posts']).'</span>
                    '.svg('created').'
                    <span aria-label="'.$l[8].'">'.fdate($data['created']).'</span>
                    <button data-f="sb" aria-label="'.$l[9].'">'.svg('more-h').'</button>
                </div>
            </div>
            ';
        }

    }
) === 0) {
    require '../include/error.php';
}

// ----- Posts -----
// Private profile
if ($cat === 2 && !$is_liked) {
    $main .=
    '<span class="end center">
        '.$l[16].'
    </span>';
}
// Public profile
else {
    // Set media for post category
    function media($cat, $pid) {
        global $id;

        switch ($cat) {
            // Image // TODO
            case '2':
                $cat = '<img src="/uc/s/'.$id.'/'.$pid.'-0.webp" alt="Image" loading="lazy" width="540">';
                break;
            // Video // TODO
            case '3':
                $cat = '[todo-video]';
                break;
            // Audio // TODO
            case '4':
                $cat = '[todo-audio]';
                break;
            // Poll // TODO
            case '5':
                $cat = '[todo-poll]';
                break;
        }

        return '<div class="media">'.$cat.'</div>';
    };

    $rows = squery(
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
                    <button data-f="sc">'.$data['name'].'</button>&nbsp;•&nbsp;
                    <button data-f="sd" data-unix="'.$data['created'].'">'.fdate($data['created']).'</button>
                    <button data-f="se" aria-label="'.$l[9].'">'.svg('more-h').'</button>
                </div>
                <p>'.fstring($data['description']).'</p>
                '.($data['category'] === '1' ? '' : media($data['category'], $data['id'])).'
                <div data-id="'.$data['id'].'">
                    <button'.$data['liked'].' data-f="sf" aria-label="'.$l[10].'">'.svg('like').fnumber($data['likes']).'</button>
                    <button data-f="sg" aria-label="'.$l[11].'">'.svg('post').fnumber($data['replies']).'</button>
                    <button data-f="sh">'.svg('save').$l[12].'</button>
                    <button data-f="psig">'.svg('reply').$l[13].'</button>
                </div>
            </article>';
        }
    );
    
    if ($rows < 5) {
        $main .=
        '<span class="end center">
            '.($rows === 0 ? $l[14] : $l[15]).'
        </span>';
    }
}

$conn -> close();
$main .= '</main>';

// ----- Generate HTML -----
$is_fetch ? exit($main) : require '../include/html.php';
html('main', 'social');
?>
