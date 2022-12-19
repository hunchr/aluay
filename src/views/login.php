<?php
/**
 * Shows the login form.
 */
// Redirect if user is already logged in
if ($uid) {
    header('Location: /home'); // TODO?: /accounts
    exit();
}

$html =
'<div class="li space">
    <div class="input lower">
        <div>'.icon('person').'</div>
        <input name="uname" type="text" data-err="'.$l['uname_err'].'" placeholder="'.$l['uname_ph'].'" maxlength="20" spellcheck="false" autocomplete="username" autofocus>
    </div>
    <div class="input">
        <div>'.icon('key').'</div>
        <input name="pwd" type="password" data-err="'.$l['pwd_err'].'" placeholder="'.$l['pwd_ph'].'" maxlength="1000" autocomplete="current-password">
        <button data-f="auth.v" tabindex="-1">'.icon('visibility').'</button>
    </div>
    <button class="btn" name="dos" data-f="auth.l" data-err="'.$l['dos_err'].'">'.$l['login_btn'].'</button>
    <div>'.$l['login_note'].'&nbsp;<button class="a href" data-f="get" data-n="signup">'.$l['signup_btn'].'</button></div>
</div>';

return 'gray center';
?>
