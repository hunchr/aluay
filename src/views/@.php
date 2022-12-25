<?php
/**
 * Shows a specific user profile.
 * @param array $q username
 */
function endOfFeed($msg) {
    global $html;
    $html .= '<span class="post center gray">'.$msg.'</span>';
    return '';
};

// Exit if profile not available
if ($json['status'] !== 200) {
    return endOfFeed($json['reason'] === 'not_found' ? $l['description'] : $json['reason'].'_note');
}

require '../../views/@include/format.php';

// --- SEO ---
$l['title'] = $name = $q[0];
$l['description'] = substr($json['description'], 0, 149).'…';
$l['keywords'] = $l['title'].',user';
$og_uri = 'uc/s/'.$json['id'].'/0';

// --- Profile badges ---
$info = $json['info'];
$badges = '';
$badge_list = [
    ['','verified','storefront','','','','security','smart_toy'], // Status
    ['','explicit'], // NSFW?
    ['','lock','','',''], // Privacy
    ['','rocket_launch']  // Premium?
];

for ($i=0; $i<4; $i++) {
    $badge = $badge_list[$i][$info[$i]];

    if ($badge) {
        $badges .= icon($badge);
    }
}

// --- Profile ---
$html =
'<div class="profile post">
    <div class="profile-t">
        <img src="/'.$og_uri.'.webp" alt="'.$l['pfp_alt'].'" loading="lazy" height="40">
        <div class="overflow">
            <div>
                <span>'.$json['name'].'&nbsp;</span>'
                .$badges.
            '</div>
            <span>@'.$name.'</span>
        </div>
        <button class="a right'
        .($json['is_liked'] ?
            ' liked" data-change="'.$l['follow_btn'].'">'.$l['following_btn'] :
            '" data-change="'.$l['following_btn'].'">'.$l['follow_btn']
        ).
        '</button>
    </div>
    <p>'.$json['description'].'</p>
    <span>
        <span class="i" aria-label="'.$l['followers_label'].'" aria-hidden="true">group</span>
        '.fnumber($json['likes']).'&nbsp;
        <span class="i" aria-label="'.$l['posts_label'].'" aria-hidden="true">forum</span>
        '.fnumber($json['posts']).'&nbsp;
        <span class="i" aria-label="'.$l['created_label'].'" aria-hidden="true">event</span>
        '.fdate($json['created']).'&nbsp;
        <span class="i" aria-label="'.$l['language_label'].'" aria-hidden="true">language</span>
        '.$json['language'].'
        <button class="right" data-f="" aria-label="'.$l['more_aria'].'">'.icon('more_horiz').'</button>
    </span>
</div>';

// --- Posts ---
// No posts
if ($json['posts_fetched'] === 0) {
    return endOfFeed($l['no_posts_note']);
}

foreach ($json['post_list'] as $post) {
    $html .=
    '<article class="post">
        <div class="post-t">
            <button data-f="post.n" class="overflow"><span>@'.$name.'</span></button>
            <span>&nbsp;•&nbsp;</span>
            <button data-f="post.t" data-unix="'.$post['created'].'"><span>'.fdate($post['created']).'</span></button>
            <span>&nbsp;'.// TODO: badges
            '</span>
            <button class="right" data-f="" aria-label="'.$l['more_aria'].'">'.icon('more_horiz').'</button>
        </div>
        <p>'.$post['text'].'</p>
        <div data-id="'.$post['id'].'">
            <button class="liked" data-f="post.l" aria-label="Likes">'.icon('favorite').fnumber($post['replies']).'</button>
            <button data-f="post.c" aria-label="'.$l['replies_aria'].'">'.icon('forum').fnumber($post['replies']).'</button>
            <button data-f="post.s">'.icon('star').$l['save_label'].'</button>
            <button data-f="post.r">'.icon('reply').$l['reply_label'].'</button>
        </div>
    </article>
    ';
}

// Reached end of feed
if ($json['posts_fetched'] !== 5) {
    endOfFeed($l['end_of_feed_note']);
}

return '';
?>
