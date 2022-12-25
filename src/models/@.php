<?php
/**
 * Get user data.
 */
require '../../models/@include/#sql.php';

$name = $q[0];

// Validate username
if (!preg_match('/^[a-z][a-z0-9-]{0,18}[a-z0-9]$/', $name)) {
    return send(404, 'not_found');
}

// ----- Get profile -----
[$json, $num_rows] = query(
    'SELECT s.*, UNIX_TIMESTAMP(s.created) AS created, u.language,'
    .($uid ?
        'IF ((
            SELECT uid
            FROM liked_subs l
            WHERE l.uid = '.$uid.'
            AND l.sid = s.id
        ), TRUE, FALSE) ':
        'NULL '
    ).
    'AS is_liked
    FROM users u
    INNER JOIN subs s
    ON u.id = s.id
    WHERE u.uname = "'.$name.'";'
);

if ($num_rows === 0) {
    return send(404, 'not_found');
}

// --- Convert info attribute ---
preg_match('/(...)(.)(...)(.)/', str_pad(decbin($json['info']), 8, '0', 0), $info);
array_shift($info);

$info = array_map(function($b) {
    return bindec($b);
}, $info);
$json['info'] = $info;

// Exit if archived/moved/banned
if ($info[2] >= 5) {
    // TODO: moved
    return send(404, $info[2] === '5' ? 'archived' : 'banned');
}

// Exit if private
if ($info[2] === '1' && !$json['is_liked']) {
    return send(403, 'private');
}

// ----- Get posts -----
$json['post_list'] = [];
[$json['post_list'], $json['posts_fetched']] = query(
    'SELECT p.*, UNIX_TIMESTAMP(p.created) AS created,
    IF (p.pid, CONCAT("&", (
        SELECT c.cname
        FROM communities c
        WHERE c.id = p.pid
    )), "@'.$name.'") as parent,'
    .($uid ?
        'IF ((
            SELECT uid
            FROM liked_posts l
            WHERE l.uid = '.$uid.'
            AND l.pid = p.id
        ), TRUE, FALSE) ':
        'NULL '
    ).
    'AS is_liked
    FROM posts p
    WHERE p.uid = '.$json['id'].'
    AND NOT p.type = 0
    ORDER BY p.created DESC
    LIMIT 5;',
    false,
    true
);

send(200);
?>
