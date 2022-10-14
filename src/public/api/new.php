<?php
/**
 * Creates a post.
 * @param array $_POST['new'] parent_id, badges, text
 */
require '../../include/api.php';

// ----- Get JSON -----
$new = isset($_POST['new']) ? json_decode($_POST['new']) : [];

if (count($new) !== 3 || !preg_match('/^null|\d+$/', $new[0])) {
    send('Invalid Arguments|Please do not try to edit the HTML or JavaScript code.|Okay', 403); // TODO: lang
}

// --- Description ---
$text = preg_replace('/\n\n+/', '\n', htmlspecialchars(trim($new[2])));
$text_len = strlen($text);

if ($text_len === 0 || $text_len > 1e4) {
    send('Invalid Post Length|The post length must be between 1 and 10,000 characters long, but this post is '.$text_len.' characters long.|Okay', 403); // TODO: lang
}

// --- Category ---
$cat = 1;

// ----- Get files -----
$files = [];
$media_cnt = count($_FILES);

if ($media_cnt) {
    $file_type = substr($_FILES[0]['type'], 0, -strpos($_FILES[0]['type'], '/'));

    // --- Validate files ---
    foreach ($_FILES as $file) {
        if (
            $file['error'] !== 0 ||
            $file['size'] > 1.25e6 ||
            substr($file['type'], 0, -strpos($file['type'], '/')) !== $file_type
        ) {
            send('ERR_CORRUPTED_FILE', 403); // TODO: lang
        }
    }

    // Images
    if ($file_type === 'image') {
        $cat = 2;

        for ($i=0; $i<$media_cnt; $i++) {
            $im = new Imagick();
            $im -> readImage($_FILES[$i]['tmp_name']);

            if (!$im -> valid()) {
                send('ERR_CORRUPTED_FILE', 403); // TODO: lang
            }
            
            // --- Resize (LANCZOS) ---
            $width = $im -> getImageWidth();
            $height = $im -> getImageHeight();

            if ($width / $height > 5 || $height / $width > 2.5) {
                send('ERR_ASPECT_RATIO', 403); // TODO: lang
            }

            if ($width > 1080) {
                $im -> resizeImage(1080, 0, 22, 0);
                $height = $im -> getImageHeight();
            }

            if ($height > 1080) {
                $im -> resizeImage(0, 1080, 22, 0);
            }

            // --- Convert to WEBP ---
            $im -> setImageFormat('webp');
            $im -> setImageCompression(21);
            $im -> setImageCompressionQuality(35);
            $im -> stripImage();

            // --- Save ---
            array_push($files, $im);
        }
    }
    else {
        echo $file_type; // TODO: remove
        send('ERR_FILE_TYPE_NOT_SUPPORTED', 403); // TODO: lang
    }
}

// ----- Create post -----
require '../../include/sql.php';

[$pid] = query(
    'INSERT INTO posts (uid, pid, info, text)
    VALUES ('.$uid.','.$new[0].','.($new[1] ? '2' : '')
        .($media_cnt ? str_pad($media_cnt, 3, '0', 0) : '000').$cat.',"'.$text.'");
    UPDATE subs
    SET posts = posts + 1
    WHERE id = '.$uid.';',
    false,
    null
);
$pid = 'uc/s/'.$uid.'/'.$pid.'-';

// ----- Upload files -----
if ($media_cnt) {
    $media_cnt++;

    for ($i=1; $i<$media_cnt; $i++) {
        file_put_contents($pid.$i.'.webp', $files[$i]);
    }
}
?>
