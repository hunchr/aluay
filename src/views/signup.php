<?php
/**
 * Shows the signup form.
 */
// Redirect if user is already logged in
if ($uid) {
    header('Location: /home'); // TODO?: /add-account
    exit();
}

$html =
'<div class="li space">
    <div class="input lower">
        '.icon('person').'
        <input name="uname" type="text" data-err="'.$l['uname_err'].'" placeholder="'.$l['uname_ph'].'" maxlength="20" spellcheck="false" autocomplete="off" autofocus>
        <button data-f="auth.i" data-info="'.$l['uname_popup'].'" tabindex="-1">'.icon('info').'</button>
    </div>
    <div class="input lower">
        '.icon('email').'
        <input name="mail" type="email" data-err="'.$l['email_err'].'" placeholder="'.$l['email_ph'].'" maxlength="100" spellcheck="false" autocomplete="off">
    </div>
    <div class="input">
        '.icon('key').'
        <input name="pwd" type="password" data-err="'.$l['pwd_err'].'" placeholder="'.$l['pwd_ph'].'" maxlength="1000" autocomplete="new-password">
        <button data-f="auth.v" tabindex="-1">'.icon('visibility').'</button>
    </div>
    <div class="input">
        '.icon('key').'
        <input name="pwd_conf" type="password" data-err="'.$l['pwd_confirm_err'].'" placeholder="'.$l['pwd_confirm_ph'].'" maxlength="1000" autocomplete="new-password">
        <button data-f="auth.v" tabindex="-1">'.icon('visibility').'</button>
    </div>
    <button class="btn" data-f="auth.s">'.$l['signup_btn'].'</button>
    <div>'.$l['signup_note'].'&nbsp;<button class="a href" data-f="get" data-n="login">'.$l['login_btn'].'</button></div>
</div>
<div class="li space hidden">
    <div class="input">
        '.icon('password').'
        <input name="verify" type="text" data-err="'.$l['verify_err'].'" placeholder="'.$l['verify_ph'].'" maxlength="16" spellcheck="false" autocomplete="off">
    </div>
    <button class="btn" data-f="auth.e">'.$l['verify_btn'].'</button>
    <div>'.$l['verify_note'].'&nbsp;<button class="a href" data-f="auth.r">'.$l['verify_resend_btn'].'</button></div>
</div>';

return 'gray center';
?>
