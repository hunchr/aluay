<?php
/**
 * Created a post.
 */
// ----- Get JSON -----
$new = isset($_POST['new']) ? json_decode($_POST['new']) : [];

if (count($new) !== 4 || !preg_match('/^null|\d+$/', $new[0])) {
    exit('Invalid Arguments|Please do not try to edit the HTML or JavaScript code.|Okay'); // TODO: lang
}

// --- Description ---
$desc = preg_replace('/\n\n+/', '\n', htmlspecialchars(trim($new[3])));
$len = strlen($desc);

if ($len === 0 || $len > 1e4) {
    exit('Invalid Post Length|The post length must be between 1 and 10,000 characters long, but this post is '.$len.' characters long.|Okay'); // TODO: lang
}

// --- Category ---
$cat = 1; // TODO

// --- Badges ---
$badges = substr(
    ($new[1] == 1 ? 'spoiler,' : '').
    ($new[2] == 1 ? 'nsfw,' : ''),
    0, -1
);

require '../include/sql/multi-query.php';

query(
    'INSERT INTO posts (uid, pid, category, badges, description)
    VALUES ('.$uid.','.$new[0].','.$cat.',"'.$badges.'","'.$desc.'");
    UPDATE subs
    SET posts = posts + 1
    WHERE id = '.$uid.';'
);
?>
