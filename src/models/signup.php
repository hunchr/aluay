<?php
/**
 * Creates a new account.
 */
// Verify email
if (isset($_POST['verify'], $_SESSION['signup'])) {
    $data = $_SESSION['signup'];

    // Check if code is valid
    if ($data[0] !== $_POST['verify']) {
        send(403, 'verify');
    }
    
    require '../../models/@include/#sql.php';
    require '../../models/@include/#pwd.php';

    // Create account
    $uid = '../uc/s/'.query(
        'INSERT INTO subs (name)
        VALUES ("'.$data[1].'");
        INSERT INTO users (id, uname, email, password, language)
        VALUES (LAST_INSERT_ID(),"'.$data[1].'","'.$data[2].'","'.pwd($data[3], false).'", "'.$lang.'");',
        false,
        null
    )[0];

    // Create directory
    mkdir($uid, 0666, true);
    copy('../uc/s/0/0.webp', $uid.'/0.webp');
    session_destroy();
    exit();
}

// Sign up
if (isset($_POST['uname'], $_POST['mail'], $_POST['pwd'])) {
    $name = strtolower($_POST['uname']);
    $mail = strtolower($_POST['mail']);
    $pwd = htmlspecialchars($_POST['pwd']);

    // Validate inputs // TODO: Check if username contains forbidden words
    if ($pwd !== $_POST['pwd_conf']) {
        send(403, 'pwd_conf');
    }
    if (!preg_match('/^[a-z][a-z0-9-]{0,18}[a-z0-9]$/', $name)) {
        send(403, 'uname');
    }
    if (!preg_match('/^[\w!#$%&\'*+\/=?^`{|}~-]+(\.[\w!#$%&\'*+\/=?^`{|}~-]+)*@([a-z0-9-]+\.)+[a-z]{2,24}$/', $mail)) {
        send(403, 'mail');
    }
    if (!(
        preg_match('/[^a-z0-9]/i', $pwd) &&
        preg_match('/\d/', $pwd) &&
        preg_match('/[A-Z]/', $pwd) &&
        preg_match('/[a-z]/', $pwd) &&
        strlen($pwd) > 7)
    ) {
        send(403, 'pwd');
    }
    
    require '../../models/@include/#sql.php';

    // Check if username is available
    query(
        'SELECT uname
        FROM users
        WHERE uname = "'.$name.'";',
        false,
        function() {
            global $name;
            global $mail;
            global $pwd;

            // Create verification pin
            $pin = substr(str_shuffle('BCFGHJKLMNPRSTVWXZbcdfghjkmnpqrstvwxz256789'), 29);
            $_SESSION['signup'] = [$pin, $name, $mail, $pwd];

            // TODO: Send verification email with pin
            send(200, 'OK');
        }
    );
            
    send(403, 'uname');
}
?>
