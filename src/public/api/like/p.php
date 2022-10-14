<?php
/**
 * Likes a post.
 * @param int $id post_id
 */
require '../../include/sql-like.php';

lquery(
    'SELECT uid
    FROM liked_posts
    WHERE uid = '.$uid.'
    AND pid = '.$id.'
    LIMIT 1;',
    'INSERT INTO liked_posts
    VALUES ('.$uid.','.$id.');
    UPDATE posts
    SET likes = likes + 1
    WHERE id = '.$id.';',
    'DELETE FROM liked_posts
    WHERE uid = '.$uid.'
    AND pid = '.$id.'
    LIMIT 1;
    UPDATE posts
    SET likes = likes - 1
    WHERE id = '.$id.';'
);
?>
