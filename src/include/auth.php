<?php
/**
 * User login.
 */
// Login
if (isset($_COOKIE['auth'])) {
    exit('Login'); // TODO: Login with cookies
    return 'TODO';
}
// Guest
else {
    function getLang() {
        preg_match_all('/[a-z]{2}(-[A-Z]{2})?/', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $langs);
        $langs = $langs[0];

        $available_langs = [ // TODO: Add new languages
            'en' => 'en-US',
            'en-AU' => 'en-US',
            'de' => 'de-DE',
        ];

        foreach ($langs as $lang) {
            if (array_key_exists($lang, $available_langs)) {
                return $available_langs[$lang];
            }
        }

        return 'en-US';
    }

    return getLang();
}
?>
