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
        exit($l[10]);
    }

    $name = strtolower($_POST['0']);
    $pwd = htmlspecialchars($_POST['1']);

    // Validate inputs
    if (!preg_match('/^[a-z][a-z0-9-]{0,18}[a-z0-9]$/', $name)) {
        exit($l[8]);
    }
    if (!(
        preg_match('/[^a-z0-9]/i', $pwd) &&
        preg_match('/\d/', $pwd) &&
        preg_match('/[A-Z]/', $pwd) &&
        preg_match('/[a-z]/', $pwd) &&
        strlen($pwd) > 7)
    ) {
        exit($l[9]);
    }

    require '../include/sql.php';

    // Get user data
    [$data, $row_cnt, $conn] = query(
        'SELECT id, uname, password, language, preferences
        FROM users
        WHERE uname = "'.$name.'";',
        true,
        function() {
            global $l;
            exit($l[8]);
        }
    );

    // Compare passwords
    if (!pwd($pwd, $data['password'])) {
        exit($l[9]);
    }

    // Generate auth token
    $uid = $_SESSION['uid'] = $data['id'];
    $lang = $_SESSION['lang'] = $data['language'];
    $time = date('Y-m-d H:i:s');
    $token = base64_encode(implode('|', [
        $name, $lang, $data['preferences'], $time, base64_encode(random_bytes(96))
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
            <input class="lower" type="text" placeholder="'.$l[3].'" maxlength="20" spellcheck="false" autocomplete="username" autofocus>
        </div>
        <div class="input">
            <div>'.svg('key').'</div>
            <input type="password" placeholder="'.$l[4].'" maxlength="1000" autocomplete="current-password">
            <button data-f="login.v" tabindex="-1">'.svg('visibility').'</button>
        </div>
        <button class="btn" data-f="login.s">'.$l[5].'</button>
        <div>'.$l[6].'&nbsp;<button class="a" data-f="get" data-n="signup">'.$l[7].'</button></div>
    </div>';

    send('gray center');
}
?>
