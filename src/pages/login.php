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

    // Compare passwords // TODO: Implement three strikes rule
    if (!pwd($pwd, $data['password'])) {
        exit($l[9]);
    }

    // Generate auth token
    $uid = $_SESSION['uid'] = $data['id'];
    $lang = $_SESSION['lang'] = $data['language'];
    $time = date('Y-m-d H:i:s');
    $token = base64_encode($name.'|'.$lang.'|'.$data['preferences'].'|'.$time.'|'.base64_encode(random_bytes(96)));
    $conn -> query(
        'INSERT INTO auth
        VALUES ('.$uid.',"'.pwd($token, false).'","'.$time.'");'
    );
    $conn -> close();

    // Set cookie to remember user (expires after 2 years)
    setrawcookie('auth', $token, time() + 6.307e7, "/");
}
// Show login form
else {
    $main = 
    '<main class="form center vis" data-title="'.$l[0].'" data-url="login">
        <div class="space center">
            <input class="lower" type="text" placeholder="'.$l[3].'" maxlength="20" spellcheck="false" autocomplete="username" autofocus>
            <input type="password" placeholder="'.$l[4].'" maxlength="1000" autocomplete="current-password">
            <button class="blue" data-f="Aa">'.$l[5].'</button>
            <span>'.$l[6].'&nbsp;<button class="a" data-f="__" data-n="signup">'.$l[7].'</button></span>
        </div>
    </main>';
    
    // Generate HTML
    $is_fetch ? exit($main) : require '../include/html.php';
    html('main', 'social');
}
?>
