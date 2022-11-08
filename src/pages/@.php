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
        'IF ((
            SELECT uid
            FROM liked_subs l
            WHERE l.uid = '.$uid.'
            AND l.sid = s.id
        ), TRUE, FALSE) AS liked ':
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
        $og_uri = 'uc/s/'.$id.'/0';
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
            $l[1] = $cat === 3 ? $l['acc_archived_note'] : $l['acc_banned_note'];
            require '../include/error.php';
        }
        // Public profile
        else {
            $l['title'] = $q[0];
            $l['description'] = strlen($data['description']) >= 150 ?
                substr($data['description'], 0, 149).'…' :
                $data['description'];
            $l['keywords'] = $l['title'].',user';
            $main =
            '<div class="profile">
                <div class="profile-t">
                    <img src="/'.$og_uri.'.webp" alt="'.$l['pfp_alt'].'" loading="lazy" width="40">
                    <div>
                        <div><span>'.$data['name'].'</span>'.$badges.'</div>
                        <div><span>@'.$l['title'].'</span></div>
                    </div>
                    <button data-f="sa" class="a'
                    .($is_liked ?
                        ' liked" data-change="'.$l['follow_btn'].'">'.$l['following_btn'] :
                        '" data-change="'.$l['following_btn'].'>'.$l['follow_btn']
                    ).
                    '</button>
                </div>
                <p>'.fstring($data['description']).'</p>
                <div>
                    '.svg('community').'
                    <span aria-label="'.$l['followers_label'].'">'.fnumber($data['likes']).'</span>
                    '.svg('post').'
                    <span aria-label="'.$l['posts_label'].'">'.fnumber($data['posts']).'</span>
                    '.svg('created').'
                    <span aria-label="'.$l['created_label'].'">'.fdate($data['created']).'</span>
                    <button data-f="sb" aria-label="'.$l['more_aria'].'">'.svg('more-h').'</button>
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
    $main .= '<span class="end center">'.$l['acc_private_note'].'</span>';
}
// Public profile
else {
    // Set media for post category
    function media($pid, $cat, $cnt) {
        global $id;

        switch ($cat) {
            // Image // TODO
            case '2':
            case '3':
                $cat =
                '<div class="media-c'.($cat === 3 ? ' pixel' : '').'">
                    <button data-f="media.pi">'.svg('expand-left').'</button>
                    <button data-f="media.ni">'.svg('expand-right').'</button>
                    <span>1</span>
                    <span>/ '.$cnt.'</span>
                    <button data-f="media.f">'.svg('fullscreen').svg('fullscreen-exit').'</button>
                </div>
                <img src="/uc/s/'.$id.'/'.$pid.'-1.webp" alt="Image" loading="lazy" width="540">';
                break;
            // Video // TODO
            case '4':
                $cat =
                '<div class="media-p"><div></div></div>
                <div class="media-c">
                    <button data-f="media.p">'.svg('play').'</button>
                    <button data-f="media.rp">'.svg('replay').'</button>
                    <button data-f="media.fw">'.svg('forward').'</button>
                    <span>00:00</span>
                    <span>/ '.$cnt.'</span>
                    <button data-f="media.v">'.svg('volume-up').'</button>
                    <button data-f="media.s">'.svg('speed').'</button>
                    <button data-f="media.f">'.svg('fullscreen').svg('fullscreen-exit').'</button>
                </div>
                <video width="540">
                    <source src="/uc/s/'.$id.'/'.$pid.'.mp4" type="video/mp4">
                </video>';
                break;
            // Audio // TODO
            case '5':
                $cat = '[todo-audio]';
                break;
            // Live // TODO
            case '6':
                $cat = '[todo-live]';
                break;
            // Poll // TODO
            case '7':
                $cat = '[todo-poll]';
                break;
        }

        return '<div class="media">'.$cat.'</div>';
    };

    // Post badges
    $badgeList = [
        [],
        ['edit'],
        ['spoiler'],
        ['nsfw'],
        ['promoted'],
        ['mod'],
        ['spoiler','nsfw'],
        ['edit','spoiler'],
        ['edit','nsfw'],
        ['edit','spoiler','nsfw']
    ];

    $rows = squery(
        'SELECT p.*, UNIX_TIMESTAMP(p.created) AS created,
        IF (p.pid, CONCAT("&", (
            SELECT c.cname
            FROM communities c
            WHERE c.id = p.pid
        )), "@'.$q[0].'") as name,'
        .($uid ?
            'IF ((
                SELECT uid
                FROM liked_posts l
                WHERE l.uid = '.$uid.'
                AND l.pid = p.id
            ), " class=\"liked\"", NULL) AS liked ':
            'NULL AS liked '
        ).
        'FROM posts p
        WHERE p.uid = '.$id.'
        AND NOT p.category = 0
        ORDER BY p.created DESC
        LIMIT 5;',
        function($data) {
            global $l;
            global $main;
            global $badgeList;

            $info = str_pad($data['info'], 5, '0', 0);
            $media_cnt = substr($info, 0, -1);
            $badges = implode('', array_map(function($badge) {
                return svg($badge);
            }, $badgeList[$info[3]]));
            $main .=
            '<article class="post">
                <div>
                    <button data-f="post.n">'.$data['name'].'</button>&nbsp;•&nbsp;
                    <button data-f="post.t" data-unix="'.$data['created'].'">'.fdate($data['created']).'</button>
                    <button data-f="more.p" aria-label="'.$l['more_aria'].'">'.svg('more-h').'</button>
                    '.$badges.'
                </div>
                <p>'.fstring($data['text']).'</p>
                '.($data['category'] === 1 ? '' : media($data['id'], $data['category'], $media_cnt)).'
                <div data-id="'.$data['id'].'">
                    <button'.$data['liked'].' data-f="post.l" aria-label="'.$l['likes_aria'].'">'
                        .svg('like').fnumber($data['likes']).
                    '</button>
                    <button data-f="post.sr" aria-label="'.$l['replies_aria'].'">'
                        .svg('post').fnumber($data['replies']).
                    '</button>
                    <button data-f="post.s">'.svg('save').$l['save_label'].'</button>
                    <button data-f="post.r">'.svg('reply').$l['reply_label'].'</button>
                </div>
            </article>';
        }
    );
    
    if ($rows < 5) {
        $main .= '<span class="end center">'.($rows === 0 ? $l['no_posts_note'] : $l['end_of_feed_note']).'</span>';
    }
}

$conn -> close();

send('');
?>
