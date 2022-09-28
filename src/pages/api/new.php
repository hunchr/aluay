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
$cat = 1;

// --- Badges ---
$badges = substr(
    ($new[1] == 1 ? 'spoiler,' : '').
    ($new[2] == 1 ? 'nsfw,' : ''),
    0, -1
);

// ----- Get files -----
$fileCount = count($_FILES);

if ($fileCount) {
    preg_match('/\w+/', $_FILES[0]['type'], $fileType);
    $fileType = $fileType[0];
    $files = [];

    // --- Validate files ---
    foreach ($_FILES as $file) {
        if ($file['error'] !== 0) {
            exit('ERR_CORRUPTED_FILE');
        }
        if (substr($file['type'], "indexOf('/')") !== $fileType) {
            echo $file['type'].' instead of '.$fileType.'\n';
            exit('ERR_INCONSISTENT_FILE_TYPE');
        }
        if ($file['size'] > 1.25e6) {
            exit('ERR_FILE_SIZE_EXCEEDED');
        }
    }

    // Images
    if ($fileType === 'image') {
        $cat = 2;

        for ($i=0; $i<$fileCount; $i++) {
            $im = new Imagick();
            $im -> readImage($_FILES[$i]['tmp_name']);

            if (!$im -> valid()) {
                exit('ERR_CORRUPTED_FILE');
            }

            $format = $im -> getImageFormat();

            // if (!preg_match('/PNG|JPEG/', $format)) {
            //     exit('ERR_UNSUPPORTED_FILE_TYPE');
            // }
            
            // --- Resize (LANCZOS) ---
            $width = $im -> getImageWidth();
            $height = $im -> getImageHeight();

            if ($width / $height > 5 || $height / $width > 2.5) {
                exit('ERR_ASPECT_RATIO');
            }

            if ($width > 1280) {
                $im -> resizeImage(1280, 0, 22, 0);
                $height = $im -> getImageHeight();
            }

            if ($height > 1280) {
                $im -> resizeImage(0, 1280, 22, 0);
            }

            // --- Convert to WEBP ---
            $im -> setImageFormat('webp');
            $im -> setImageCompression(21);
            $im -> setImageCompressionQuality(40);
            // $im -> setImageCompressionQuality(65);
            $im -> stripImage();

            // --- Save ---
            array_push($files, $im);
        }
    }
    else {
        exit('ERR_FILE_TYPE_NOT_SUPPORTED');
    }
}

// ----- Create post -----
require '../include/sql/self-query.php';

$conn = conn();

if (!$conn -> multi_query(
    'INSERT INTO posts (uid, pid, category, badges, description)
    VALUES ('.$uid.','.$new[0].','.$cat.',"'.$badges.'","'.$desc.'");
    UPDATE subs
    SET posts = posts + 1
    WHERE id = '.$uid.';'
)) {
    exit('ERR_DB_REFUSED');
}

// ----- Upload files -----
if ($fileCount) {
    // --- Get post ID ---
    $pid = $conn -> query(
        'SELECT id
        FROM posts
        WHERE uid = '.$uid.'
        ORDER BY created DESC
        LIMIT 1;'
    );

    $pid = $pid -> fetch_assoc()['id'];
    $conn -> close();

    // --- Save files in directory ---
    $pid = 'uc/s/'.$uid.'/'.$pid.'-';

    for ($i=0; $i<$fileCount; $i++) {
        file_put_contents($pid.$i.'.webp', $im);
    }
}
?>
