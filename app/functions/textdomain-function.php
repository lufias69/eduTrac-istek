<?php
if (!defined('BASE_PATH'))
    exit('No direct script access allowed');
/**
 * eduTrac SIS Text Domain.
 *  
 * @license GPLv3
 * 
 * @since       6.1.13
 * @package     eduTrac SIS
 * @author      Joshua Parker <joshmac3@icloud.com>
 */
$t = new \Gettext\Translator();
$t->register();

/**
 * Loads the current or default locale.
 * 
 * @since 6.1.09
 * @return string The locale.
 */
function load_core_locale()
{
    $app = \Liten\Liten::getInstance();

    if (is_readable(BASE_PATH . 'config.php')) {
        $locale = get_option('etsis_core_locale');
    } else {
        $locale = 'en_US';
    }
    return $app->hook->apply_filter('core_locale', $locale);
}

/**
 * Load a .mo file into the text domain.
 *
 * @since 6.1.13
 *
 * @param string $domain Text domain. Unique identifier for retrieving translated strings.
 * @param string $path Path to the .mo file.
 * @return bool True on success, false on failure.
 */
function load_textdomain($domain, $path)
{
    global $t;

    $app = \Liten\Liten::getInstance();

    /**
     * Filter text domain and/or .mo file path for loading translations.
     *
     * @since 6.1.13
     *
     * @param bool   $override Should we override textdomain?. Default is false.
     * @param string $domain   Text domain. Unique identifier for retrieving translated strings.
     * @param string $path   Path to the .mo file.
     */
    $plugin_override = $app->hook->apply_filter('override_load_textdomain', false, $domain, $path);

    if (true == $plugin_override) {
        return true;
    }

    /**
     * Fires before the .mo translation file is loaded.
     *
     * @since 6.1.13
     *
     * @param string $domain Text domain. Unique identifier for retrieving translated strings.
     * @param string $path Path to the .mo file.
     */
    $app->hook->do_action('load_textdomain', $domain, $path);

    /**
     * Filter .mo file path for loading translations for a specific text domain.
     *
     * @since 6.1.13
     *
     * @param string $path Path to the .mo file.
     * @param string $domain Text domain. Unique identifier for retrieving translated strings.
     */
    $mofile = $app->hook->apply_filter('load_textdomain_mofile', $path, $domain);
    // Load only if the .mo file is present and readable.
    if (!is_readable($mofile)) {
        return false;
    }

    $translations = \Gettext\Translations::fromMoFile($mofile);
    $t->loadTranslations($translations);

    return true;
}

/**
 * Load default translated strings based on locale.
 *
 * @since 6.1.09
 * @param string $domain Text domain. Unique identifier for retrieving translated strings.
 * @param string $path Path to the .mo file.
 * @return bool True on success, false on failure.
 */
function load_default_textdomain($domain, $path)
{
    $locale = load_core_locale();

    $mopath = $path . $domain . '-' . $locale . '.mo';

    $return = load_textdomain($domain, $mopath);

    return $return;
}

/**
 * Load a plugin's translated strings.
 *
 * If the path is not given then it will be the root of the plugin directory.
 *
 * @since 6.1.09
 * @param string $domain          Unique identifier for retrieving translated strings
 * @param string $plugin_rel_path Optional. Relative path to ETSIS_PLUGIN_DIR where the locale directory resides.
 *                                Default false.
 * @return bool True when textdomain is successfully loaded, false otherwise.
 */
function load_plugin_textdomain($domain, $plugin_rel_path = false)
{
    $app = \Liten\Liten::getInstance();

    $locale = load_core_locale();
    /**
     * Filter a plugin's locale.
     * 
     * @since 6.1.09
     * 
     * @param string $locale The plugin's current locale.
     * @param string $domain Text domain. Unique identifier for retrieving translated strings.
     */
    $plugin_locale = $app->hook->apply_filter('plugin_locale', $locale, $domain);

    if ($plugin_rel_path !== false) {
        $path = ETSIS_PLUGIN_DIR . $plugin_rel_path . DS;
    } else {
        $path = ETSIS_PLUGIN_DIR;
    }

    $mofile = $path . $domain . '-' . $plugin_locale . '.mo';
    if ($loaded = load_textdomain($domain, $mofile)) {
        return $loaded;
    }

    return false;
}

/**
 * Retrieves a list of available locales.
 * 
 * @since 6.1.09
 * @param string $active
 */
function etsis_dropdown_languages($active = '')
{
    if (function_exists('enable_url_ssl')) {
        $protocol = 'https://';
    } else {
        $protocol = 'http://';
    }

    $locales = _file_get_contents($protocol . 'etsis.s3.amazonaws.com/core/1.1/translations.json');
    $json = json_decode($locales, true);
    foreach ($json as $locale) {
        echo '<option value="' . $locale['language'] . '"' . selected($active, $locale['language'], false) . '>' . $locale['native_name'] . '</option>';
    }
}

/**
 * Converts all accent characters to ASCII characters.
 *
 * If there are no accent characters, then the string given is just returned.
 *
 * **Accent characters converted:**
 *
 * Currency signs:
 *
 * |   Code   | Glyph | Replacement |     Description     |
 * | -------- | ----- | ----------- | ------------------- |
 * | U+00A3   | ??     | (empty)     | British Pound sign  |
 * | U+20AC   | ???     | E           | Euro sign           |
 *
 * Decompositions for Latin-1 Supplement:
 *
 * |  Code   | Glyph | Replacement |               Description              |
 * | ------- | ----- | ----------- | -------------------------------------- |
 * | U+00AA  | ??     | a           | Feminine ordinal indicator             |
 * | U+00BA  | ??     | o           | Masculine ordinal indicator            |
 * | U+00C0  | ??     | A           | Latin capital letter A with grave      |
 * | U+00C1  | ??     | A           | Latin capital letter A with acute      |
 * | U+00C2  | ??     | A           | Latin capital letter A with circumflex |
 * | U+00C3  | ??     | A           | Latin capital letter A with tilde      |
 * | U+00C4  | ??     | A           | Latin capital letter A with diaeresis  |
 * | U+00C5  | ??     | A           | Latin capital letter A with ring above |
 * | U+00C6  | ??     | AE          | Latin capital letter AE                |
 * | U+00C7  | ??     | C           | Latin capital letter C with cedilla    |
 * | U+00C8  | ??     | E           | Latin capital letter E with grave      |
 * | U+00C9  | ??     | E           | Latin capital letter E with acute      |
 * | U+00CA  | ??     | E           | Latin capital letter E with circumflex |
 * | U+00CB  | ??     | E           | Latin capital letter E with diaeresis  |
 * | U+00CC  | ??     | I           | Latin capital letter I with grave      |
 * | U+00CD  | ??     | I           | Latin capital letter I with acute      |
 * | U+00CE  | ??     | I           | Latin capital letter I with circumflex |
 * | U+00CF  | ??     | I           | Latin capital letter I with diaeresis  |
 * | U+00D0  | ??     | D           | Latin capital letter Eth               |
 * | U+00D1  | ??     | N           | Latin capital letter N with tilde      |
 * | U+00D2  | ??     | O           | Latin capital letter O with grave      |
 * | U+00D3  | ??     | O           | Latin capital letter O with acute      |
 * | U+00D4  | ??     | O           | Latin capital letter O with circumflex |
 * | U+00D5  | ??     | O           | Latin capital letter O with tilde      |
 * | U+00D6  | ??     | O           | Latin capital letter O with diaeresis  |
 * | U+00D8  | ??     | O           | Latin capital letter O with stroke     |
 * | U+00D9  | ??     | U           | Latin capital letter U with grave      |
 * | U+00DA  | ??     | U           | Latin capital letter U with acute      |
 * | U+00DB  | ??     | U           | Latin capital letter U with circumflex |
 * | U+00DC  | ??     | U           | Latin capital letter U with diaeresis  |
 * | U+00DD  | ??     | Y           | Latin capital letter Y with acute      |
 * | U+00DE  | ??     | TH          | Latin capital letter Thorn             |
 * | U+00DF  | ??     | s           | Latin small letter sharp s             |
 * | U+00E0  | ??     | a           | Latin small letter a with grave        |
 * | U+00E1  | ??     | a           | Latin small letter a with acute        |
 * | U+00E2  | ??     | a           | Latin small letter a with circumflex   |
 * | U+00E3  | ??     | a           | Latin small letter a with tilde        |
 * | U+00E4  | ??     | a           | Latin small letter a with diaeresis    |
 * | U+00E5  | ??     | a           | Latin small letter a with ring above   |
 * | U+00E6  | ??     | ae          | Latin small letter ae                  |
 * | U+00E7  | ??     | c           | Latin small letter c with cedilla      |
 * | U+00E8  | ??     | e           | Latin small letter e with grave        |
 * | U+00E9  | ??     | e           | Latin small letter e with acute        |
 * | U+00EA  | ??     | e           | Latin small letter e with circumflex   |
 * | U+00EB  | ??     | e           | Latin small letter e with diaeresis    |
 * | U+00EC  | ??     | i           | Latin small letter i with grave        |
 * | U+00ED  | ??     | i           | Latin small letter i with acute        |
 * | U+00EE  | ??     | i           | Latin small letter i with circumflex   |
 * | U+00EF  | ??     | i           | Latin small letter i with diaeresis    |
 * | U+00F0  | ??     | d           | Latin small letter Eth                 |
 * | U+00F1  | ??     | n           | Latin small letter n with tilde        |
 * | U+00F2  | ??     | o           | Latin small letter o with grave        |
 * | U+00F3  | ??     | o           | Latin small letter o with acute        |
 * | U+00F4  | ??     | o           | Latin small letter o with circumflex   |
 * | U+00F5  | ??     | o           | Latin small letter o with tilde        |
 * | U+00F6  | ??     | o           | Latin small letter o with diaeresis    |
 * | U+00F8  | ??     | o           | Latin small letter o with stroke       |
 * | U+00F9  | ??     | u           | Latin small letter u with grave        |
 * | U+00FA  | ??     | u           | Latin small letter u with acute        |
 * | U+00FB  | ??     | u           | Latin small letter u with circumflex   |
 * | U+00FC  | ??     | u           | Latin small letter u with diaeresis    |
 * | U+00FD  | ??     | y           | Latin small letter y with acute        |
 * | U+00FE  | ??     | th          | Latin small letter Thorn               |
 * | U+00FF  | ??     | y           | Latin small letter y with diaeresis    |
 *
 * Decompositions for Latin Extended-A:
 *
 * |  Code   | Glyph | Replacement |                    Description                    |
 * | ------- | ----- | ----------- | ------------------------------------------------- |
 * | U+0100  | ??     | A           | Latin capital letter A with macron                |
 * | U+0101  | ??     | a           | Latin small letter a with macron                  |
 * | U+0102  | ??     | A           | Latin capital letter A with breve                 |
 * | U+0103  | ??     | a           | Latin small letter a with breve                   |
 * | U+0104  | ??     | A           | Latin capital letter A with ogonek                |
 * | U+0105  | ??     | a           | Latin small letter a with ogonek                  |
 * | U+01006 | ??     | C           | Latin capital letter C with acute                 |
 * | U+0107  | ??     | c           | Latin small letter c with acute                   |
 * | U+0108  | ??     | C           | Latin capital letter C with circumflex            |
 * | U+0109  | ??     | c           | Latin small letter c with circumflex              |
 * | U+010A  | ??     | C           | Latin capital letter C with dot above             |
 * | U+010B  | ??     | c           | Latin small letter c with dot above               |
 * | U+010C  | ??     | C           | Latin capital letter C with caron                 |
 * | U+010D  | ??     | c           | Latin small letter c with caron                   |
 * | U+010E  | ??     | D           | Latin capital letter D with caron                 |
 * | U+010F  | ??     | d           | Latin small letter d with caron                   |
 * | U+0110  | ??     | D           | Latin capital letter D with stroke                |
 * | U+0111  | ??     | d           | Latin small letter d with stroke                  |
 * | U+0112  | ??     | E           | Latin capital letter E with macron                |
 * | U+0113  | ??     | e           | Latin small letter e with macron                  |
 * | U+0114  | ??     | E           | Latin capital letter E with breve                 |
 * | U+0115  | ??     | e           | Latin small letter e with breve                   |
 * | U+0116  | ??     | E           | Latin capital letter E with dot above             |
 * | U+0117  | ??     | e           | Latin small letter e with dot above               |
 * | U+0118  | ??     | E           | Latin capital letter E with ogonek                |
 * | U+0119  | ??     | e           | Latin small letter e with ogonek                  |
 * | U+011A  | ??     | E           | Latin capital letter E with caron                 |
 * | U+011B  | ??     | e           | Latin small letter e with caron                   |
 * | U+011C  | ??     | G           | Latin capital letter G with circumflex            |
 * | U+011D  | ??     | g           | Latin small letter g with circumflex              |
 * | U+011E  | ??     | G           | Latin capital letter G with breve                 |
 * | U+011F  | ??     | g           | Latin small letter g with breve                   |
 * | U+0120  | ??     | G           | Latin capital letter G with dot above             |
 * | U+0121  | ??     | g           | Latin small letter g with dot above               |
 * | U+0122  | ??     | G           | Latin capital letter G with cedilla               |
 * | U+0123  | ??     | g           | Latin small letter g with cedilla                 |
 * | U+0124  | ??     | H           | Latin capital letter H with circumflex            |
 * | U+0125  | ??     | h           | Latin small letter h with circumflex              |
 * | U+0126  | ??     | H           | Latin capital letter H with stroke                |
 * | U+0127  | ??     | h           | Latin small letter h with stroke                  |
 * | U+0128  | ??     | I           | Latin capital letter I with tilde                 |
 * | U+0129  | ??     | i           | Latin small letter i with tilde                   |
 * | U+012A  | ??     | I           | Latin capital letter I with macron                |
 * | U+012B  | ??     | i           | Latin small letter i with macron                  |
 * | U+012C  | ??     | I           | Latin capital letter I with breve                 |
 * | U+012D  | ??     | i           | Latin small letter i with breve                   |
 * | U+012E  | ??     | I           | Latin capital letter I with ogonek                |
 * | U+012F  | ??     | i           | Latin small letter i with ogonek                  |
 * | U+0130  | ??     | I           | Latin capital letter I with dot above             |
 * | U+0131  | ??     | i           | Latin small letter dotless i                      |
 * | U+0132  | ??     | IJ          | Latin capital ligature IJ                         |
 * | U+0133  | ??     | ij          | Latin small ligature ij                           |
 * | U+0134  | ??     | J           | Latin capital letter J with circumflex            |
 * | U+0135  | ??     | j           | Latin small letter j with circumflex              |
 * | U+0136  | ??     | K           | Latin capital letter K with cedilla               |
 * | U+0137  | ??     | k           | Latin small letter k with cedilla                 |
 * | U+0138  | ??     | k           | Latin small letter Kra                            |
 * | U+0139  | ??     | L           | Latin capital letter L with acute                 |
 * | U+013A  | ??     | l           | Latin small letter l with acute                   |
 * | U+013B  | ??     | L           | Latin capital letter L with cedilla               |
 * | U+013C  | ??     | l           | Latin small letter l with cedilla                 |
 * | U+013D  | ??     | L           | Latin capital letter L with caron                 |
 * | U+013E  | ??     | l           | Latin small letter l with caron                   |
 * | U+013F  | ??     | L           | Latin capital letter L with middle dot            |
 * | U+0140  | ??     | l           | Latin small letter l with middle dot              |
 * | U+0141  | ??     | L           | Latin capital letter L with stroke                |
 * | U+0142  | ??     | l           | Latin small letter l with stroke                  |
 * | U+0143  | ??     | N           | Latin capital letter N with acute                 |
 * | U+0144  | ??     | n           | Latin small letter N with acute                   |
 * | U+0145  | ??     | N           | Latin capital letter N with cedilla               |
 * | U+0146  | ??     | n           | Latin small letter n with cedilla                 |
 * | U+0147  | ??     | N           | Latin capital letter N with caron                 |
 * | U+0148  | ??     | n           | Latin small letter n with caron                   |
 * | U+0149  | ??     | n           | Latin small letter n preceded by apostrophe       |
 * | U+014A  | ??     | N           | Latin capital letter Eng                          |
 * | U+014B  | ??     | n           | Latin small letter Eng                            |
 * | U+014C  | ??     | O           | Latin capital letter O with macron                |
 * | U+014D  | ??     | o           | Latin small letter o with macron                  |
 * | U+014E  | ??     | O           | Latin capital letter O with breve                 |
 * | U+014F  | ??     | o           | Latin small letter o with breve                   |
 * | U+0150  | ??     | O           | Latin capital letter O with double acute          |
 * | U+0151  | ??     | o           | Latin small letter o with double acute            |
 * | U+0152  | ??     | OE          | Latin capital ligature OE                         |
 * | U+0153  | ??     | oe          | Latin small ligature oe                           |
 * | U+0154  | ??     | R           | Latin capital letter R with acute                 |
 * | U+0155  | ??     | r           | Latin small letter r with acute                   |
 * | U+0156  | ??     | R           | Latin capital letter R with cedilla               |
 * | U+0157  | ??     | r           | Latin small letter r with cedilla                 |
 * | U+0158  | ??     | R           | Latin capital letter R with caron                 |
 * | U+0159  | ??     | r           | Latin small letter r with caron                   |
 * | U+015A  | ??     | S           | Latin capital letter S with acute                 |
 * | U+015B  | ??     | s           | Latin small letter s with acute                   |
 * | U+015C  | ??     | S           | Latin capital letter S with circumflex            |
 * | U+015D  | ??     | s           | Latin small letter s with circumflex              |
 * | U+015E  | ??     | S           | Latin capital letter S with cedilla               |
 * | U+015F  | ??     | s           | Latin small letter s with cedilla                 |
 * | U+0160  | ??     | S           | Latin capital letter S with caron                 |
 * | U+0161  | ??     | s           | Latin small letter s with caron                   |
 * | U+0162  | ??     | T           | Latin capital letter T with cedilla               |
 * | U+0163  | ??     | t           | Latin small letter t with cedilla                 |
 * | U+0164  | ??     | T           | Latin capital letter T with caron                 |
 * | U+0165  | ??     | t           | Latin small letter t with caron                   |
 * | U+0166  | ??     | T           | Latin capital letter T with stroke                |
 * | U+0167  | ??     | t           | Latin small letter t with stroke                  |
 * | U+0168  | ??     | U           | Latin capital letter U with tilde                 |
 * | U+0169  | ??     | u           | Latin small letter u with tilde                   |
 * | U+016A  | ??     | U           | Latin capital letter U with macron                |
 * | U+016B  | ??     | u           | Latin small letter u with macron                  |
 * | U+016C  | ??     | U           | Latin capital letter U with breve                 |
 * | U+016D  | ??     | u           | Latin small letter u with breve                   |
 * | U+016E  | ??     | U           | Latin capital letter U with ring above            |
 * | U+016F  | ??     | u           | Latin small letter u with ring above              |
 * | U+0170  | ??     | U           | Latin capital letter U with double acute          |
 * | U+0171  | ??     | u           | Latin small letter u with double acute            |
 * | U+0172  | ??     | U           | Latin capital letter U with ogonek                |
 * | U+0173  | ??     | u           | Latin small letter u with ogonek                  |
 * | U+0174  | ??     | W           | Latin capital letter W with circumflex            |
 * | U+0175  | ??     | w           | Latin small letter w with circumflex              |
 * | U+0176  | ??     | Y           | Latin capital letter Y with circumflex            |
 * | U+0177  | ??     | y           | Latin small letter y with circumflex              |
 * | U+0178  | ??     | Y           | Latin capital letter Y with diaeresis             |
 * | U+0179  | ??     | Z           | Latin capital letter Z with acute                 |
 * | U+017A  | ??     | z           | Latin small letter z with acute                   |
 * | U+017B  | ??     | Z           | Latin capital letter Z with dot above             |
 * | U+017C  | ??     | z           | Latin small letter z with dot above               |
 * | U+017D  | ??     | Z           | Latin capital letter Z with caron                 |
 * | U+017E  | ??     | z           | Latin small letter z with caron                   |
 * | U+017F  | ??     | s           | Latin small letter long s                         |
 * | U+01A0  | ??     | O           | Latin capital letter O with horn                  |
 * | U+01A1  | ??     | o           | Latin small letter o with horn                    |
 * | U+01AF  | ??     | U           | Latin capital letter U with horn                  |
 * | U+01B0  | ??     | u           | Latin small letter u with horn                    |
 * | U+01CD  | ??     | A           | Latin capital letter A with caron                 |
 * | U+01CE  | ??     | a           | Latin small letter a with caron                   |
 * | U+01CF  | ??     | I           | Latin capital letter I with caron                 |
 * | U+01D0  | ??     | i           | Latin small letter i with caron                   |
 * | U+01D1  | ??     | O           | Latin capital letter O with caron                 |
 * | U+01D2  | ??     | o           | Latin small letter o with caron                   |
 * | U+01D3  | ??     | U           | Latin capital letter U with caron                 |
 * | U+01D4  | ??     | u           | Latin small letter u with caron                   |
 * | U+01D5  | ??     | U           | Latin capital letter U with diaeresis and macron  |
 * | U+01D6  | ??     | u           | Latin small letter u with diaeresis and macron    |
 * | U+01D7  | ??     | U           | Latin capital letter U with diaeresis and acute   |
 * | U+01D8  | ??     | u           | Latin small letter u with diaeresis and acute     |
 * | U+01D9  | ??     | U           | Latin capital letter U with diaeresis and caron   |
 * | U+01DA  | ??     | u           | Latin small letter u with diaeresis and caron     |
 * | U+01DB  | ??     | U           | Latin capital letter U with diaeresis and grave   |
 * | U+01DC  | ??     | u           | Latin small letter u with diaeresis and grave     |
 *
 * Decompositions for Latin Extended-B:
 *
 * |   Code   | Glyph | Replacement |                Description                |
 * | -------- | ----- | ----------- | ----------------------------------------- |
 * | U+0218   | ??     | S           | Latin capital letter S with comma below   |
 * | U+0219   | ??     | s           | Latin small letter s with comma below     |
 * | U+021A   | ??     | T           | Latin capital letter T with comma below   |
 * | U+021B   | ??     | t           | Latin small letter t with comma below     |
 *
 * Vowels with diacritic (Chinese, Hanyu Pinyin):
 *
 * |   Code   | Glyph | Replacement |                      Description                      |
 * | -------- | ----- | ----------- | ----------------------------------------------------- |
 * | U+0251   | ??     | a           | Latin small letter alpha                              |
 * | U+1EA0   | ???     | A           | Latin capital letter A with dot below                 |
 * | U+1EA1   | ???     | a           | Latin small letter a with dot below                   |
 * | U+1EA2   | ???     | A           | Latin capital letter A with hook above                |
 * | U+1EA3   | ???     | a           | Latin small letter a with hook above                  |
 * | U+1EA4   | ???     | A           | Latin capital letter A with circumflex and acute      |
 * | U+1EA5   | ???     | a           | Latin small letter a with circumflex and acute        |
 * | U+1EA6   | ???     | A           | Latin capital letter A with circumflex and grave      |
 * | U+1EA7   | ???     | a           | Latin small letter a with circumflex and grave        |
 * | U+1EA8   | ???     | A           | Latin capital letter A with circumflex and hook above |
 * | U+1EA9   | ???     | a           | Latin small letter a with circumflex and hook above   |
 * | U+1EAA   | ???     | A           | Latin capital letter A with circumflex and tilde      |
 * | U+1EAB   | ???     | a           | Latin small letter a with circumflex and tilde        |
 * | U+1EA6   | ???     | A           | Latin capital letter A with circumflex and dot below  |
 * | U+1EAD   | ???     | a           | Latin small letter a with circumflex and dot below    |
 * | U+1EAE   | ???     | A           | Latin capital letter A with breve and acute           |
 * | U+1EAF   | ???     | a           | Latin small letter a with breve and acute             |
 * | U+1EB0   | ???     | A           | Latin capital letter A with breve and grave           |
 * | U+1EB1   | ???     | a           | Latin small letter a with breve and grave             |
 * | U+1EB2   | ???     | A           | Latin capital letter A with breve and hook above      |
 * | U+1EB3   | ???     | a           | Latin small letter a with breve and hook above        |
 * | U+1EB4   | ???     | A           | Latin capital letter A with breve and tilde           |
 * | U+1EB5   | ???     | a           | Latin small letter a with breve and tilde             |
 * | U+1EB6   | ???     | A           | Latin capital letter A with breve and dot below       |
 * | U+1EB7   | ???     | a           | Latin small letter a with breve and dot below         |
 * | U+1EB8   | ???     | E           | Latin capital letter E with dot below                 |
 * | U+1EB9   | ???     | e           | Latin small letter e with dot below                   |
 * | U+1EBA   | ???     | E           | Latin capital letter E with hook above                |
 * | U+1EBB   | ???     | e           | Latin small letter e with hook above                  |
 * | U+1EBC   | ???     | E           | Latin capital letter E with tilde                     |
 * | U+1EBD   | ???     | e           | Latin small letter e with tilde                       |
 * | U+1EBE   | ???     | E           | Latin capital letter E with circumflex and acute      |
 * | U+1EBF   | ???     | e           | Latin small letter e with circumflex and acute        |
 * | U+1EC0   | ???     | E           | Latin capital letter E with circumflex and grave      |
 * | U+1EC1   | ???     | e           | Latin small letter e with circumflex and grave        |
 * | U+1EC2   | ???     | E           | Latin capital letter E with circumflex and hook above |
 * | U+1EC3   | ???     | e           | Latin small letter e with circumflex and hook above   |
 * | U+1EC4   | ???     | E           | Latin capital letter E with circumflex and tilde      |
 * | U+1EC5   | ???     | e           | Latin small letter e with circumflex and tilde        |
 * | U+1EC6   | ???     | E           | Latin capital letter E with circumflex and dot below  |
 * | U+1EC7   | ???     | e           | Latin small letter e with circumflex and dot below    |
 * | U+1EC8   | ???     | I           | Latin capital letter I with hook above                |
 * | U+1EC9   | ???     | i           | Latin small letter i with hook above                  |
 * | U+1ECA   | ???     | I           | Latin capital letter I with dot below                 |
 * | U+1ECB   | ???     | i           | Latin small letter i with dot below                   |
 * | U+1ECC   | ???     | O           | Latin capital letter O with dot below                 |
 * | U+1ECD   | ???     | o           | Latin small letter o with dot below                   |
 * | U+1ECE   | ???     | O           | Latin capital letter O with hook above                |
 * | U+1ECF   | ???     | o           | Latin small letter o with hook above                  |
 * | U+1ED0   | ???     | O           | Latin capital letter O with circumflex and acute      |
 * | U+1ED1   | ???     | o           | Latin small letter o with circumflex and acute        |
 * | U+1ED2   | ???     | O           | Latin capital letter O with circumflex and grave      |
 * | U+1ED3   | ???     | o           | Latin small letter o with circumflex and grave        |
 * | U+1ED4   | ???     | O           | Latin capital letter O with circumflex and hook above |
 * | U+1ED5   | ???     | o           | Latin small letter o with circumflex and hook above   |
 * | U+1ED6   | ???     | O           | Latin capital letter O with circumflex and tilde      |
 * | U+1ED7   | ???     | o           | Latin small letter o with circumflex and tilde        |
 * | U+1ED8   | ???     | O           | Latin capital letter O with circumflex and dot below  |
 * | U+1ED9   | ???     | o           | Latin small letter o with circumflex and dot below    |
 * | U+1EDA   | ???     | O           | Latin capital letter O with horn and acute            |
 * | U+1EDB   | ???     | o           | Latin small letter o with horn and acute              |
 * | U+1EDC   | ???     | O           | Latin capital letter O with horn and grave            |
 * | U+1EDD   | ???     | o           | Latin small letter o with horn and grave              |
 * | U+1EDE   | ???     | O           | Latin capital letter O with horn and hook above       |
 * | U+1EDF   | ???     | o           | Latin small letter o with horn and hook above         |
 * | U+1EE0   | ???     | O           | Latin capital letter O with horn and tilde            |
 * | U+1EE1   | ???     | o           | Latin small letter o with horn and tilde              |
 * | U+1EE2   | ???     | O           | Latin capital letter O with horn and dot below        |
 * | U+1EE3   | ???     | o           | Latin small letter o with horn and dot below          |
 * | U+1EE4   | ???     | U           | Latin capital letter U with dot below                 |
 * | U+1EE5   | ???     | u           | Latin small letter u with dot below                   |
 * | U+1EE6   | ???     | U           | Latin capital letter U with hook above                |
 * | U+1EE7   | ???     | u           | Latin small letter u with hook above                  |
 * | U+1EE8   | ???     | U           | Latin capital letter U with horn and acute            |
 * | U+1EE9   | ???     | u           | Latin small letter u with horn and acute              |
 * | U+1EEA   | ???     | U           | Latin capital letter U with horn and grave            |
 * | U+1EEB   | ???     | u           | Latin small letter u with horn and grave              |
 * | U+1EEC   | ???     | U           | Latin capital letter U with horn and hook above       |
 * | U+1EED   | ???     | u           | Latin small letter u with horn and hook above         |
 * | U+1EEE   | ???     | U           | Latin capital letter U with horn and tilde            |
 * | U+1EEF   | ???     | u           | Latin small letter u with horn and tilde              |
 * | U+1EF0   | ???     | U           | Latin capital letter U with horn and dot below        |
 * | U+1EF1   | ???     | u           | Latin small letter u with horn and dot below          |
 * | U+1EF2   | ???     | Y           | Latin capital letter Y with grave                     |
 * | U+1EF3   | ???     | y           | Latin small letter y with grave                       |
 * | U+1EF4   | ???     | Y           | Latin capital letter Y with dot below                 |
 * | U+1EF5   | ???     | y           | Latin small letter y with dot below                   |
 * | U+1EF6   | ???     | Y           | Latin capital letter Y with hook above                |
 * | U+1EF7   | ???     | y           | Latin small letter y with hook above                  |
 * | U+1EF8   | ???     | Y           | Latin capital letter Y with tilde                     |
 * | U+1EF9   | ???     | y           | Latin small letter y with tilde                       |
 *
 * German (`de_DE`), German formal (`de_DE_formal`), German (Switzerland) formal (`de_CH`),
 * and German (Switzerland) informal (`de_CH_informal`) locales:
 *
 * |   Code   | Glyph | Replacement |               Description               |
 * | -------- | ----- | ----------- | --------------------------------------- |
 * | U+00C4   | ??     | Ae          | Latin capital letter A with diaeresis   |
 * | U+00E4   | ??     | ae          | Latin small letter a with diaeresis     |
 * | U+00D6   | ??     | Oe          | Latin capital letter O with diaeresis   |
 * | U+00F6   | ??     | oe          | Latin small letter o with diaeresis     |
 * | U+00DC   | ??     | Ue          | Latin capital letter U with diaeresis   |
 * | U+00FC   | ??     | ue          | Latin small letter u with diaeresis     |
 * | U+00DF   | ??     | ss          | Latin small letter sharp s              |
 *
 * Danish (`da_DK`) locale:
 *
 * |   Code   | Glyph | Replacement |               Description               |
 * | -------- | ----- | ----------- | --------------------------------------- |
 * | U+00C6   | ??     | Ae          | Latin capital letter AE                 |
 * | U+00E6   | ??     | ae          | Latin small letter ae                   |
 * | U+00D8   | ??     | Oe          | Latin capital letter O with stroke      |
 * | U+00F8   | ??     | oe          | Latin small letter o with stroke        |
 * | U+00C5   | ??     | Aa          | Latin capital letter A with ring above  |
 * | U+00E5   | ??     | aa          | Latin small letter a with ring above    |
 *
 * Catalan (`ca`) locale:
 *
 * |   Code   | Glyph | Replacement |               Description               |
 * | -------- | ----- | ----------- | --------------------------------------- |
 * | U+00B7   | l??l   | ll          | Flown dot (between two Ls)              |
 *
 * @since 6.2.10
 *
 * @param string $string Text that might have accent characters
 * @return string Filtered string with replaced "nice" characters.
 */
function etsis_remove_accents($string)
{
    if (!preg_match('/[\x80-\xff]/', $string))
        return $string;

    if (mb_check_encoding($string, 'UTF-8')) {
        $chars = array(
            // Decompositions for Greek Upper Case Supplement
            '??' => 'A', '??' => 'A',
            '??' => 'V', '??' => 'G',
            '??' => 'D', '??' => 'E',
            '??' => 'E', '??' => 'Z',
            '??' => 'I', '??' => 'I',
            '??' => 'Th', '??' => 'I',
            '??' => 'I', '??' => 'I',
            '??' => 'I', '??' => 'K',
            '??' => 'L', '??' => 'M',
            '??' => 'N', '??' => 'X',
            '??' => 'O', '??' => 'O',
            '??' => 'P', '??' => 'R',
            '??' => 'S', '??' => 'T',
            '??' => 'Y', '??' => 'Y',
            '??' => 'Y', '??' => 'Y',
            '??' => 'F', '??' => 'Ch',
            '??' => 'Ps', '??' => 'O',
            '??' => 'O',
            // Decompositions for Greek Lower Case Supplement
            '??' => 'a', '??' => 'a',
            '??' => 'v', '??' => 'g',
            '??' => 'd', '??' => 'e',
            '??' => 'e', '??' => 'z',
            '??' => 'i', '??' => 'i',
            '??' => 'th', '??' => 'i',
            '??' => 'i', '??' => 'i',
            '??' => 'k', '??' => 'l',
            '??' => 'm', '??' => 'n',
            '??' => 'x', '??' => 'o',
            '??' => 'o', '??' => 'p',
            '??' => 'r', '??' => 's',
            '??' => 't', '??' => 'y',
            '??' => 'y', '??' => 'y',
            '??' => 'f', '??' => 'ch',
            '??' => 'ps', '??' => 'o',
            '??' => 'o',
            // Decompositions for Latin-1 Supplement
            '??' => 'a', '??' => 'o',
            '??' => 'A', '??' => 'A',
            '??' => 'A', '??' => 'A',
            '??' => 'A', '??' => 'A',
            '??' => 'AE', '??' => 'C',
            '??' => 'E', '??' => 'E',
            '??' => 'E', '??' => 'E',
            '??' => 'I', '??' => 'I',
            '??' => 'I', '??' => 'I',
            '??' => 'D', '??' => 'N',
            '??' => 'O', '??' => 'O',
            '??' => 'O', '??' => 'O',
            '??' => 'O', '??' => 'U',
            '??' => 'U', '??' => 'U',
            '??' => 'U', '??' => 'Y',
            '??' => 'TH', '??' => 's',
            '??' => 'a', '??' => 'a',
            '??' => 'a', '??' => 'a',
            '??' => 'a', '??' => 'a',
            '??' => 'ae', '??' => 'c',
            '??' => 'e', '??' => 'e',
            '??' => 'e', '??' => 'e',
            '??' => 'i', '??' => 'i',
            '??' => 'i', '??' => 'i',
            '??' => 'd', '??' => 'n',
            '??' => 'o', '??' => 'o',
            '??' => 'o', '??' => 'o',
            '??' => 'o', '??' => 'o',
            '??' => 'u', '??' => 'u',
            '??' => 'u', '??' => 'u',
            '??' => 'y', '??' => 'th',
            '??' => 'y', '??' => 'O',
            // Decompositions for Latin Extended-A
            '??' => 'A', '??' => 'a',
            '??' => 'A', '??' => 'a',
            '??' => 'A', '??' => 'a',
            '??' => 'C', '??' => 'c',
            '??' => 'C', '??' => 'c',
            '??' => 'C', '??' => 'c',
            '??' => 'C', '??' => 'c',
            '??' => 'D', '??' => 'd',
            '??' => 'D', '??' => 'd',
            '??' => 'E', '??' => 'e',
            '??' => 'E', '??' => 'e',
            '??' => 'E', '??' => 'e',
            '??' => 'E', '??' => 'e',
            '??' => 'E', '??' => 'e',
            '??' => 'G', '??' => 'g',
            '??' => 'G', '??' => 'g',
            '??' => 'G', '??' => 'g',
            '??' => 'G', '??' => 'g',
            '??' => 'H', '??' => 'h',
            '??' => 'H', '??' => 'h',
            '??' => 'I', '??' => 'i',
            '??' => 'I', '??' => 'i',
            '??' => 'I', '??' => 'i',
            '??' => 'I', '??' => 'i',
            '??' => 'I', '??' => 'i',
            '??' => 'IJ', '??' => 'ij',
            '??' => 'J', '??' => 'j',
            '??' => 'K', '??' => 'k',
            '??' => 'k', '??' => 'L',
            '??' => 'l', '??' => 'L',
            '??' => 'l', '??' => 'L',
            '??' => 'l', '??' => 'L',
            '??' => 'l', '??' => 'L',
            '??' => 'l', '??' => 'N',
            '??' => 'n', '??' => 'N',
            '??' => 'n', '??' => 'N',
            '??' => 'n', '??' => 'n',
            '??' => 'N', '??' => 'n',
            '??' => 'O', '??' => 'o',
            '??' => 'O', '??' => 'o',
            '??' => 'O', '??' => 'o',
            '??' => 'OE', '??' => 'oe',
            '??' => 'R', '??' => 'r',
            '??' => 'R', '??' => 'r',
            '??' => 'R', '??' => 'r',
            '??' => 'S', '??' => 's',
            '??' => 'S', '??' => 's',
            '??' => 'S', '??' => 's',
            '??' => 'S', '??' => 's',
            '??' => 'T', '??' => 't',
            '??' => 'T', '??' => 't',
            '??' => 'T', '??' => 't',
            '??' => 'U', '??' => 'u',
            '??' => 'U', '??' => 'u',
            '??' => 'U', '??' => 'u',
            '??' => 'U', '??' => 'u',
            '??' => 'U', '??' => 'u',
            '??' => 'U', '??' => 'u',
            '??' => 'W', '??' => 'w',
            '??' => 'Y', '??' => 'y',
            '??' => 'Y', '??' => 'Z',
            '??' => 'z', '??' => 'Z',
            '??' => 'z', '??' => 'Z',
            '??' => 'z', '??' => 's',
            // Decompositions for Latin Extended-B
            '??' => 'S', '??' => 's',
            '??' => 'T', '??' => 't',
            // Euro Sign
            '???' => 'E',
            // GBP (Pound) Sign
            '??' => '',
            // Vowels with diacritic (Vietnamese)
            // unmarked
            '??' => 'O', '??' => 'o',
            '??' => 'U', '??' => 'u',
            // grave accent
            '???' => 'A', '???' => 'a',
            '???' => 'A', '???' => 'a',
            '???' => 'E', '???' => 'e',
            '???' => 'O', '???' => 'o',
            '???' => 'O', '???' => 'o',
            '???' => 'U', '???' => 'u',
            '???' => 'Y', '???' => 'y',
            // hook
            '???' => 'A', '???' => 'a',
            '???' => 'A', '???' => 'a',
            '???' => 'A', '???' => 'a',
            '???' => 'E', '???' => 'e',
            '???' => 'E', '???' => 'e',
            '???' => 'I', '???' => 'i',
            '???' => 'O', '???' => 'o',
            '???' => 'O', '???' => 'o',
            '???' => 'O', '???' => 'o',
            '???' => 'U', '???' => 'u',
            '???' => 'U', '???' => 'u',
            '???' => 'Y', '???' => 'y',
            // tilde
            '???' => 'A', '???' => 'a',
            '???' => 'A', '???' => 'a',
            '???' => 'E', '???' => 'e',
            '???' => 'E', '???' => 'e',
            '???' => 'O', '???' => 'o',
            '???' => 'O', '???' => 'o',
            '???' => 'U', '???' => 'u',
            '???' => 'Y', '???' => 'y',
            // acute accent
            '???' => 'A', '???' => 'a',
            '???' => 'A', '???' => 'a',
            '???' => 'E', '???' => 'e',
            '???' => 'O', '???' => 'o',
            '???' => 'O', '???' => 'o',
            '???' => 'U', '???' => 'u',
            // dot below
            '???' => 'A', '???' => 'a',
            '???' => 'A', '???' => 'a',
            '???' => 'A', '???' => 'a',
            '???' => 'E', '???' => 'e',
            '???' => 'E', '???' => 'e',
            '???' => 'I', '???' => 'i',
            '???' => 'O', '???' => 'o',
            '???' => 'O', '???' => 'o',
            '???' => 'O', '???' => 'o',
            '???' => 'U', '???' => 'u',
            '???' => 'U', '???' => 'u',
            '???' => 'Y', '???' => 'y',
            // Vowels with diacritic (Chinese, Hanyu Pinyin)
            '??' => 'a',
            // macron
            '??' => 'U', '??' => 'u',
            // acute accent
            '??' => 'U', '??' => 'u',
            // caron
            '??' => 'A', '??' => 'a',
            '??' => 'I', '??' => 'i',
            '??' => 'O', '??' => 'o',
            '??' => 'U', '??' => 'u',
            '??' => 'U', '??' => 'u',
            // grave accent
            '??' => 'U', '??' => 'u',
        );

        // Used for locale-specific rules
        $locale = load_core_locale();

        if ('de_DE' == $locale || 'de_DE_formal' == $locale || 'de_CH' == $locale || 'de_CH_informal' == $locale) {
            $chars['??'] = 'Ae';
            $chars['??'] = 'ae';
            $chars['??'] = 'Oe';
            $chars['??'] = 'oe';
            $chars['??'] = 'Ue';
            $chars['??'] = 'ue';
            $chars['??'] = 'ss';
        } elseif ('da_DK' === $locale) {
            $chars['??'] = 'Ae';
            $chars['??'] = 'ae';
            $chars['??'] = 'Oe';
            $chars['??'] = 'oe';
            $chars['??'] = 'Aa';
            $chars['??'] = 'aa';
        } elseif ('ca' === $locale) {
            $chars['l??l'] = 'll';
        }

        $string = strtr($string, $chars);
    } else {
        $chars = [];
        // Assume ISO-8859-1 if not UTF-8
        $chars['in'] = "\x80\x83\x8a\x8e\x9a\x9e"
            . "\x9f\xa2\xa5\xb5\xc0\xc1\xc2"
            . "\xc3\xc4\xc5\xc7\xc8\xc9\xca"
            . "\xcb\xcc\xcd\xce\xcf\xd1\xd2"
            . "\xd3\xd4\xd5\xd6\xd8\xd9\xda"
            . "\xdb\xdc\xdd\xe0\xe1\xe2\xe3"
            . "\xe4\xe5\xe7\xe8\xe9\xea\xeb"
            . "\xec\xed\xee\xef\xf1\xf2\xf3"
            . "\xf4\xf5\xf6\xf8\xf9\xfa\xfb"
            . "\xfc\xfd\xff";

        $chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

        $string = strtr($string, $chars['in'], $chars['out']);
        $double_chars = [];
        $double_chars['in'] = ["\x8c", "\x9c", "\xc6", "\xd0", "\xde", "\xdf", "\xe6", "\xf0", "\xfe"];
        $double_chars['out'] = ['OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th'];
        $string = str_replace($double_chars['in'], $double_chars['out'], $string);
    }

    return $string;
}

/**
 * Sanitizes a string, or returns a fallback string.
 *
 * Specifically, HTML and PHP tags are stripped. Further actions can be added
 * via the plugin API. If $string is empty and $fallback_string is set, the latter
 * will be used.
 *
 * @since 6.2.10
 *
 * @param string $string          The string to be sanitized.
 * @param string $fallback_string Optional. A string to use if $string is empty.
 * @param string $context        Optional. The operation for which the string is sanitized
 * @return string The sanitized string.
 */
function etsis_sanitize_string($string, $fallback_string = '', $context = 'save')
{
    $app = \Liten\Liten::getInstance();

    $raw_string = $string;

    if ('save' == $context)
        $string = etsis_remove_accents($string);

    /**
     * Filters a sanitized string.
     *
     * @since 6.2.10
     *
     * @param string $string        Sanitized string.
     * @param string $raw_string    The string prior to sanitization.
     * @param string $context       The context for which the string is being sanitized.
     */
    $string = $app->hook->apply_filter('sanitize_string', $string, $raw_string, $context);

    if ('' === $string || false === $string)
        $string = $fallback_string;

    return $string;
}
