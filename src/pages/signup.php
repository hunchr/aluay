<?php
/**
 * Shows the signup form.
 * Creates a new account.
 */
// Redirect if user is already logged in
if ($uid) {
    header('Location: /home'); // TODO?: /help/signup
    exit();
}
// Create new account
if (isset($_POST['0'])) {
    // Sign up
    if (isset($_POST['1'], $_POST['2'], $_POST['3'])) {
        $name = strtolower($_POST['0']);
        $mail = strtolower($_POST['1']);
        $pwd = htmlspecialchars($_POST['2']);

        // Validate inputs // TODO: Check if username contains forbidden words
        if ($pwd !== $_POST['3']) {
            exit($l[18]);
        }
        if (!preg_match('/^[a-z][a-z0-9-]{0,18}[a-z0-9]$/', $name)) {
            exit($l[14]);
        }
        if (!preg_match('/^[\w!#$%&\'*+\/=?^`{|}~-]+(\.[\w!#$%&\'*+\/=?^`{|}~-]+)*@([a-z0-9-]+\.)+[a-z]{2,24}$/', $mail)) {
            exit($l[16]);
        }
        if (!(
            preg_match('/[^a-z0-9]/i', $pwd) &&
            preg_match('/\d/', $pwd) &&
            preg_match('/[A-Z]/', $pwd) &&
            preg_match('/[a-z]/', $pwd) &&
            strlen($pwd) > 7)
        ) {
            exit($l[17]);
        }
        
        require '../include/sql/single-query.php';

        // Check if username is available
        if (query('SELECT uname FROM users WHERE uname = "'.$name.'";')) {
            $pin = preg_replace('/[\/+l]/', 'a', strtolower(base64_encode(random_bytes(9))));
            $_SESSION['signup'] = [$pin, $name, $mail, $pwd];

            // TODO: Send verification email with pin
            exit();
        }
        else {
            exit($l[15]);
        }
    }
    // Verify email
    if (isset($_SESSION['signup'])) {
        $data = $_SESSION['signup'];

        if ($data[0] !== strtolower($_POST['0'])) {
            exit($data[0]); // TODO: exit($l[19]);
        }
        
        require '../include/sql/pwd-query.php';

        // Create account
        $conn = conn();
        $conn -> query(
            'INSERT INTO subs (name)
            VALUES ("@'.$data[1].'");' // TODO?: htmlspecialchars
        );
        $uid = $conn -> query(
            'SELECT id
            from subs
            WHERE type = 0
            AND name = "@'.$data[1].'"
            LIMIT 1;'
        );

        if ($uid -> num_rows === 0) {
            exit($l[20]); // TODO
        }

        $uid = $uid -> fetch_assoc()['id'];
        $conn -> query(
            'INSERT INTO users (id, uname, email, password, language)
            VALUES ('.$uid.',"'.$data[1].'","'.$data[2].'","'.pwd($data[3], false).'", "'.$lang.'");'
        );
        $conn -> close();

        // Create directory
        $uid = 'uc/s/'.$uid;
        mkdir($uid, 0666, true);
        copy('uc/s/0/0.webp', $uid.'/0.webp');
        session_destroy();
    }
}
// Show signup form
else {
    $main = 
    '<main class="center form vis" data-title="'.$l[0].'">
        <div>
            <input class="lower" type="text" placeholder="'.$l[3].'" maxlength="20" spellcheck="false" autocomplete="off" autofocus>
            <input class="lower" type="email" placeholder="'.$l[4].'" maxlength="100" spellcheck="false" autocomplete="off">
            <input type="password" placeholder="'.$l[5].'" maxlength="1000" autocomplete="new-password">
            <input type="password" placeholder="'.$l[6].'" maxlength="1000" autocomplete="new-password">
            <button class="btn" data-f="_b">'.$l[7].'</button>
            <span>'.$l[8].'&nbsp;<button class="a" data-f="__" data-n="login">'.$l[9].'</button></span>
        </div>
        <div class="hidden">
            <input class="lower" type="text" placeholder="'.$l[10].'" maxlength="12" spellcheck="false" autocomplete="off">
            <button class="btn" data-f="_c">'.$l[11].'</button>
            <span>'.$l[12].'&nbsp;<button class="a" data-f="_d">'.$l[13].'</button></span>
        </div>
    </main>';
    
    // Generate HTML
    $is_fetch ? exit($main) : require '../include/html.php';
    html('main', 'social');
}
?>