<?php
/**
 * Destroys the user's session.
 */
setrawcookie('auth', '', time() - 3600, '/');
unset($_SESSION['uid']);
unset($_SESSION['lang']);

header('Location: /login');
?>
