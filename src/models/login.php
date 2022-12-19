<?php
/**
 * Logs the user in.
 */
// Log in
if (isset($_POST['uname'], $_POST['pwd'])) {
    $_SESSION['strikes'] = isset($_SESSION['strikes']) ? $_SESSION['strikes'] + 1 : 0;

    // 5 strikes rule // TODO: Implement better rule with SQL logging and ip ban
    if ($_SESSION['strikes'] >= 5) {
        send(429, 'dos');
    }

    $name = strtolower($_POST['uname']);
    $pwd = htmlspecialchars($_POST['pwd']);

    // Validate inputs
    if (!preg_match('/^[a-z][a-z0-9-]{0,18}[a-z0-9]$/', $name)) {
        send(403, 'uname');
    }
    if (!(
        preg_match('/[^a-z0-9]/i', $pwd) &&
        preg_match('/\d/', $pwd) &&
        preg_match('/[A-Z]/', $pwd) &&
        preg_match('/[a-z]/', $pwd) &&
        strlen($pwd) >= 8)
    ) {
        send(403, 'pwd');
    }

    require '../../models/@include/#sql.php';
    
    // Get user data
    $data = query(
        'SELECT id, uname, password, language, display
        FROM users
        WHERE uname = "'.$name.'";',
        true,
        function() {
            send(403, 'uname');
        }
    )[0];

    require '../../models/@include/#pwd.php';
    
    // Compare passwords
    if (!pwd($pwd, $data['password'])) {
        send(403, 'pwd');
    }

    // Generate auth token
    $uid = $_SESSION['uid'] = $data['id'];
    $lang = $_SESSION['lang'] = $data['language'];
    $time = date('Y-m-d H:i:s');
    $token = base64_encode(implode('|', [
        $name, $lang, str_pad(decbin($data['display']), 8, '0', 0), $time, base64_encode(random_bytes(96))
    ]));

    // Save token in database
    query(
        'INSERT INTO auth
        VALUES ('.$uid.',"'.pwd($token, false).'","'.$time.'");',
        false,
        false
    );

    // Set cookie to remember user (expires after 2 years)
    setrawcookie('auth', $token, time() + 6.307e7, "/");
    unset($_SESSION['strikes']);
}
?>
