<?php

/**
 * Pkcs7.php
 * 
 * PHP version 5
 * 
 * @category Dcrypt
 * @package  Dcrypt
 * @author   Michael Meyer (mmeyer2k) <m.meyer2k@gmail.com>
 * @license  http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link     https://github.com/mmeyer2k/dcrypt
 */

namespace Dcrypt;

/**
 * Provides PKCS #7 padding functionality.
 * 
 * @category Dcrypt
 * @package  Dcrypt
 * @author   Michael Meyer (mmeyer2k) <m.meyer2k@gmail.com>
 * @license  http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link     https://github.com/mmeyer2k/dcrypt
 * @link     https://apigen.ci/github/mmeyer2k/dcrypt/namespace-Dcrypt.html
 */
final class Pkcs7
{

    /**
     * PKCS #7 padding function.
     * 
     * @param string  $input     String to pad
     * @param integer $blocksize Block size in bytes
     * 
     * @return string
     */
    public static function pad($input, $blocksize)
    {
        // Determine the padding string that needs to be appended.
        $pad = self::paddingString(Str::strlen($input), $blocksize);

        // Return input + padding
        return $input . $pad;
    }

    /**
     * Create the padding string that will be appended to the input.
     * 
     * @param integer $inputsize Size of the input in bytes
     * @param integer $blocksize Blocksize in bytes
     * 
     * @return integer
     */
    private static function paddingString($inputsize, $blocksize)
    {
        // Determine the amount of padding to use
        $pad = $blocksize - ($inputsize % $blocksize);

        // Create and return the padding string
        return \str_repeat(\chr($pad), $pad);
    }

    /**
     * PKCS #7 unpadding function.
     * 
     * @param string $input Padded string to unpad
     * 
     * @return string
     */
    public static function unpad($input)
    {
        // Determine the padding size by converting the final byte of the  
        // input to its decimal value
        $padsize = \ord(Str::substr($input, -1));

        // Return string minus the padding amount
        return Str::substr($input, 0, Str::strlen($input) - $padsize);
    }

}
