<?php
/**
 * Likes a sub.
 * @param int $id sub_id
 */
require '../include/sql-like.php';

lquery(
    'SELECT uid
    FROM liked_subs
    WHERE uid = '.$uid.'
    AND sid = '.$id.'
    LIMIT 1;',
    'INSERT INTO liked_subs
    VALUES ('.$uid.','.$id.');
    UPDATE subs
    SET likes = likes + 1
    WHERE id = '.$id.';',
    'DELETE FROM liked_subs
    WHERE uid = '.$uid.'
    AND sid = '.$id.'
    LIMIT 1;
    UPDATE subs
    SET likes = likes - 1
    WHERE id = '.$id.';'
);
?>
