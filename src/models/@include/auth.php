<?php
/**
 * User login.
 */
// Login
if (isset($_COOKIE['auth'])) {
    exit('Login'); // TODO: Login with cookies
}
// Get browser language from guest
else {
    function getLang() {
        preg_match_all('/[a-z]{2}(-[A-Z]{2})?/', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $langs);
        $langs = $langs[0];

        $available_langs = [ // TODO: Add new languages
            'en' => 'en-US',
            'en-AU' => 'en-US',
            'de' => 'de-DE',
        ];

        // Get preferred language
        foreach ($langs as $lang) {
            if (array_key_exists($lang, $available_langs)) {
                return $available_langs[$lang];
            }
        }

        // Use default language if no other available
        return 'en-US';
    }

    $lang = $_SESSION['lang'] = getLang();
    return $lang;
}
?>
