<?php
/**
 * Shows the login form.
 */
// Redirect if user is already logged in
if ($uid) {
    header('Location: /home'); // TODO?: /accounts
    exit();
}
// Log in
if (isset($_POST['0'], $_POST['1'])) {
    $_SESSION['strikes'] = isset($_SESSION['strikes']) ? $_SESSION['strikes'] + 1 : 0;

    // 5 strikes rule // TODO: Implement better rule with SQL logging and ip ban
    if ($_SESSION['strikes'] >= 5) {
        exit($l['dos_err']);
    }

    $name = strtolower($_POST['0']);
    $pwd = htmlspecialchars($_POST['1']);

    // Validate inputs
    if (!preg_match('/^[a-z][a-z0-9-]{0,18}[a-z0-9]$/', $name)) {
        exit($l['uname_err']);
    }
    if (!(
        preg_match('/[^a-z0-9]/i', $pwd) &&
        preg_match('/\d/', $pwd) &&
        preg_match('/[A-Z]/', $pwd) &&
        preg_match('/[a-z]/', $pwd) &&
        strlen($pwd) >= 8)
    ) {
        exit($l['pwd_err']);
    }

    require '../include/sql.php';

    // Get user data
    [$data, $row_cnt, $conn] = query(
        'SELECT id, uname, password, language, display
        FROM users
        WHERE uname = "'.$name.'";',
        true,
        function() {
            global $l;
            exit($l['uname_err']);
        }
    );

    // Compare passwords
    if (!pwd($pwd, $data['password'])) {
        exit($l['pwd_err']);
    }

    // Generate auth token
    $uid = $_SESSION['uid'] = $data['id'];
    $lang = $_SESSION['lang'] = $data['language'];
    $time = date('Y-m-d H:i:s');
    $token = base64_encode(implode('|', [
        $name, $lang, str_pad(decbin($data['display']), 8, '0', 0), $time, base64_encode(random_bytes(96))
        // $name, $lang, $data['display'], $time, base64_encode(random_bytes(96))
    ]));
    $conn -> query(
        'INSERT INTO auth
        VALUES ('.$uid.',"'.pwd($token, false).'","'.$time.'");'
    );
    $conn -> close();

    // Set cookie to remember user (expires after 2 years)
    setrawcookie('auth', $token, time() + 6.307e7, "/");
    unset($_SESSION['strikes']);
}
// Show login form
else {
    $main =
    '<div class="li space">
        <div class="input">
            <div>'.svg('profile').'</div>
            <input class="lower" type="text" placeholder="'.$l['uname_ph'].'" maxlength="20" spellcheck="false" autocomplete="username" autofocus>
        </div>
        <div class="input">
            <div>'.svg('key').'</div>
            <input type="password" placeholder="'.$l['pwd_ph'].'" maxlength="1000" autocomplete="current-password">
            <button data-f="login.v" tabindex="-1">'.svg('visibility').'</button>
        </div>
        <button class="btn" data-f="login.s">'.$l['login_btn'].'</button>
        <div>'.$l['login_note'].'&nbsp;<button class="a" data-f="get" data-n="signup">'.$l['signup_btn'].'</button></div>
    </div>';

    send('gray center');
}
?>
