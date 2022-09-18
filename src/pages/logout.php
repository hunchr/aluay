<?php
/**
 * Destroys the user's session.
 */
setrawcookie('auth', '', time() - 3600, "/");
session_destroy();

header('Location: /login');
?>
