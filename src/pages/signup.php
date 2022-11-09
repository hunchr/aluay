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
            exit($l['pwd_confirm_err']);
        }
        if (!preg_match('/^[a-z][a-z0-9-]{0,18}[a-z0-9]$/', $name)) {
            exit($l['uname_err']);
        }
        if (!preg_match('/^[\w!#$%&\'*+\/=?^`{|}~-]+(\.[\w!#$%&\'*+\/=?^`{|}~-]+)*@([a-z0-9-]+\.)+[a-z]{2,24}$/', $mail)) {
            exit($l['email_err']);
        }
        if (!(
            preg_match('/[^a-z0-9]/i', $pwd) &&
            preg_match('/\d/', $pwd) &&
            preg_match('/[A-Z]/', $pwd) &&
            preg_match('/[a-z]/', $pwd) &&
            strlen($pwd) > 7)
        ) {
            exit($l['pwd_err']);
        }
        
        require '../include/sql.php';

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
                $pin = substr(str_shuffle('BCDFGHJKLMNPQRSTVWXZbcdfghjkmnpqrstvwxz256789'), 29);
                $_SESSION['signup'] = [$pin, $name, $mail, $pwd];

                // TODO: Send verification email with pin
                exit();
            }
        );
                
        exit($l['uname_taken_err']);
    }

    // Verify email
    if (isset($_SESSION['signup'])) {
        $data = $_SESSION['signup'];

        if ($data[0] !== $_POST['0']) {
            exit($data[0]); // TODO: remove line
            exit($l['verify_err']);
        }
        
        require '../include/sql.php';

        // Create account
        [$uid] = query(
            'INSERT INTO subs (name)
            VALUES ("'.$data[1].'");
            INSERT INTO users (id, uname, email, password, language)
            VALUES (LAST_INSERT_ID(),"'.$data[1].'","'.$data[2].'","'.pwd($data[3], false).'", "'.$lang.'");',
            false,
            null
        );

        // Create directory
        $uid = 'uc/s/'.$uid;
        mkdir($uid, 0666, true);
        copy('uc/s/0/0.webp', $uid.'/0.webp');
        session_destroy();
        exit();
    }
}
// Show signup form
else {
    $main =
    '<div class="li space">
        <div class="input">
            <div>'.svg('profile').'</div>
            <input class="lower" type="text" placeholder="'.$l['uname_ph'].'" maxlength="20" spellcheck="false" autocomplete="off" autofocus>
            <button data-f="signup.i" data-info="'.$l['uname_popup'].'" tabindex="-1">'.svg('info').'</button>
        </div>
        <div class="input">
            <div>'.svg('email').'</div>
            <input class="lower" type="email" placeholder="'.$l['email_ph'].'" maxlength="100" spellcheck="false" autocomplete="off">
        </div>
        <div class="input">
            <div>'.svg('key').'</div>
            <input type="password" placeholder="'.$l['pwd_ph'].'" maxlength="1000" autocomplete="new-password">
            <button data-f="login.v" tabindex="-1">'.svg('visibility').'</button>
        </div>
        <div class="input">
            <div>'.svg('key').'</div>
            <input type="password" placeholder="'.$l['pwd_confirm_ph'].'" maxlength="1000" autocomplete="new-password">
            <button data-f="login.v" tabindex="-1">'.svg('visibility').'</button>
        </div>
        <button class="btn" data-f="signup.s">'.$l['signup_btn'].'</button>
        <div>'.$l['signup_note'].'&nbsp;<button class="a" data-f="get" data-n="login">'.$l['login_btn'].'</button></div>
    </div>
    <div class="li space hidden">
        <input type="text" placeholder="'.$l['verify_ph'].'" maxlength="16" spellcheck="false" autocomplete="off">
        <button class="btn" data-f="signup.e">'.$l['verify_btn'].'</button>
        <div>'.$l['verify_note'].'&nbsp;<button class="a" data-f="signup.r">'.$l['verify_resend_btn'].'</button></div>
    </div>';

    send('gray center');
}
?>
