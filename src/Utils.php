<?php

namespace Tron;

use IEXBase\TronAPI\Support\Base58Check;
use kornrunner\Keccak;
use phpseclib\Math\BigInteger;

class Utils
{
    /**
     * Convert from Hex
     *
     * @param $string
     * @return string
     */
    public static function fromHex($string)
    {
        if (strlen($string) == 42 && mb_substr($string, 0, 2) === '41') {
            return self::hexString2Address($string);
        }

        return self::hexString2Utf8($string);
    }

    /**
     * Convert to Hex
     *
     * @param $str
     * @return string
     */
    public static function toHex($str)
    {
        if (mb_strlen($str) == 34 && mb_substr($str, 0, 1) === 'T') {
            return self::address2HexString($str);
        };

        return self::stringUtf8toHex($str);
    }

    /**
     * Check the address before converting to Hex
     *
     * @param $sHexAddress
     * @return string
     */
    public static function address2HexString($sHexAddress)
    {
        if (strlen($sHexAddress) == 42 && mb_strpos($sHexAddress, '41') == 0) {
            return $sHexAddress;
        }
        return Base58Check::decode($sHexAddress, 0, 3);
    }

    /**
     * Check Hex address before converting to Base58
     *
     * @param $sHexString
     * @return string
     */
    public static function hexString2Address($sHexString)
    {
        if (!ctype_xdigit($sHexString)) {
            return $sHexString;
        }

        if (strlen($sHexString) < 2 || (strlen($sHexString) & 1) != 0) {
            return '';
        }

        return Base58Check::encode($sHexString, 0, false);
    }

    /**
     * Convert string to hex
     *
     * @param $sUtf8
     * @return string
     */
    public static function stringUtf8toHex($sUtf8)
    {
        return bin2hex($sUtf8);
    }

    /**
     * Convert hex to string
     *
     * @param $sHexString
     * @return string
     */
    public static function hexString2Utf8($sHexString)
    {
        return hex2bin($sHexString);
    }

    /**
     * Convert to great value
     *
     * @param $str
     * @return BigInteger
     */
    public static function toBigNumber($str)
    {
        return new BigInteger($str);
    }

    /**
     * Convert trx to float
     *
     * @param $amount
     * @return float
     */
    public static function fromTron($amount): float
    {
        return (float) bcdiv((string)$amount, (string)1e6, 8);
    }

    /**
     * Convert float to trx format
     *
     * @param $double
     * @return int
     */
    public static function toTron($double): int
    {
        return (int) bcmul((string)$double, (string)1e6, 0);
    }

    /**
     * Convert to SHA3
     *
     * @param $string
     * @param bool $prefix
     * @return string
     * @throws \Exception
     */
    public static function sha3($string, $prefix = true)
    {
        return ($prefix ? '0x' : '') . Keccak::hash($string, 256);
    }
}
