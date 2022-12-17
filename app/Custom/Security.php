<?php

/* @author: Usiobaifo Kenneth
 * @developer: Usiobaifo Kenneth
 * @year: 2022
 * @rights: Usiobaifo Kenneth
 * */

namespace App\Custom;

abstract class Security
{
    public static function timeNow(bool $isTimstamp = false){
        if($isTimstamp == true){
            return time();
        }
        return date('Y-m-d H:i:s',time());
    }

    //========monitor if admin user not logged in==========
    public static function adminBefore()
    {
        if (!Session::get('adm_email')) {
            return Utis::pageRedirect(DS . Config::get('ADMIN') . '/login/');
        }
        return true;
    }

    //======monitor if user not logged in===========
    public static function userBefore()
    {
        if (!Session::get('email')) {
            return Utis::pageRedirect('/users/login/');
        }
        return true;
    }

    //====monitor if admin user login already=============
    public static function adminAfter()
    {
        if (Session::get('adm_email')) {
            return Utis::pageRedirect(DS  . Config::get('ADMIN') . '/index/');
        }
        return false;
    }

    //======monitor if user login already========
    public static function userAfter()
    {
        if (Session::get('email')) {
            return Utis::pageRedirect('/dashboard/home/');
        }
        return false;
    }

    public static function encryptionKeyGenerationFunc()
    {
        $key = base64_encode(openssl_random_pseudo_bytes(32));
        return $key;
    }

    public static function passwordSalt(): String
    {
        $salt = md5('kkennymoreEngineeringLimitedAiruleyemwangbonusiobaifokennethiguelaihooviasouthwestlocalgov');
        return $salt;
    }

    // encryption and decryption funtions starts here-----------------------------------------------------------------------
    public static function kenProtectFunc(String $string): String
    {
        $strings = trim(strip_tags(addslashes($string)));
        return $strings;
    }

    // encrypt function----------------------------------------------------------------------------------------------------------
    public static function kenEncrypt(String $string, String $key): String
    {
        $encryption_key = base64_decode($key);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($string, 'aes-256-cbc', $encryption_key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    // decryption function----------------------------------------------------------------------------------------------------
    public static function kenDecrypt(String $string, String $key)
    {
        $encryption_key = base64_decode($key);
        @list($encrypted_data, $iv) = explode('::', base64_decode($string), 2);
        return @openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
    }

    // Salt encryption function--------------------------------------------------------------------------------------
    public static function kenhashword(String $string, String $salt): String
    {
        $strings = crypt($string, '$1$' . $salt . '$');
        return $strings;
    }

    // email matching regular expression
    public static function emailRegularExpression(String $email): String
    {
        $match = preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD', $email);
        return $match;
    }

    // Username matching regular expression
    public static function usernameRegularExpression(String $username): String
    {
        $match = preg_match('/^[a-zA-Z0-9_-]{3,20}$/', $username);
        return $match;
    }

    // password matching regular expression
    public static function passwordRegularExpression(String $password): String
    {
        $match = preg_match('((?=.*\d)(?=.*[a-z]).{6,20})', $password);
        return $match;
    }

    // this is the mobile phone number validation
    public static function mobileNumberValidation(String $number): String
    {
        $regular = preg_match('/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im', $number);
        return $regular;
    }

    // this is the mobile phone number validation
    public static function experienceNumberValidation($number)
    {
        $regular = preg_match('/^[0-9_-]{1,14}$/', $number);
        return $regular;
    }

    //==========remove underscore==============//
    public static function trimUnderscore(String $string): String
    {
        $filtered = str_replace('_', ' ', $string);
        return $filtered;
    }

    //=======add underscore=============
    public static function addUnderscore(String $string): String
    {
        $filtered = str_replace(' ', '_', $string);
        return $filtered;
    }

    //===========replace forward sign=============
    public static function replaceForwardSlashed(String $string): String
    {
        $filtered = str_replace('/', 'XSJUBFJKFH', $string);
        return $filtered;
    }

    //=============remove amper sign=============//
    public static function removeAmpersign(String $string): String
    {
        $filtered = str_replace('&', 'XSJUBFJKFH', $string);
        return $filtered;
    }

    //===========add amper sign==============//
    public static function addAmpersign(String $string): String
    {
        $filtered = str_replace('XSJUBFJKFH', '&', $string);
        return $filtered;
    }

    //======insert forward slashes================
    public static function insertForwardSlashed(String $string): String
    {
        $filtered = str_replace('XSJUBFJKFH', '/', $string);
        return $filtered;
    }

    //==========remove pound sign============//
    public static function underPoundSignRemove(String $string): String
    {
        return str_replace('#', '', $string);
    }

    //============add pound sign=============
    public static function addPoundSign(String $string): String
    {
        return str_replace(' ', '#', $string);
    }

    //=========remove underscore============
    public static function underscoreRemove(String $string): String
    {
        return str_replace('_', ' ', $string);
    }

    //==========remove dashes===============
    public static function dashesRemove(String $string): String
    {
        return str_replace('-', ' ', $string);
    }

    //===========add slashes==============
    public static function addDashes(String $string): String
    {
        return str_replace(' ', '-', $string);
    }

    //=====add spaces===================//
    public static function addSpace(String $string): String
    {
        return str_replace(' ', 'spkahfst', $string);
    }

    //========add backslashes==============//
    public static function addBackSlash(String $string): String
    {
        return str_replace('', '\\', $string);
    }

    //============remove forward slashes============//
    public static function removeBackSlash(String $string): String
    {
        return str_replace('\\', '', $string);
    }

    //===========replace bad words=============
    public static function replaceBadWord(String $string): String
    {
        $words = array('fuck', 'sex', 'pussy', 'penis', 'ass', 'dick', 'bitch');
        $replace = array('f**k', 's*x', 'p***y', 'p***s', 'a*s', 'd**k', 'b***h');
        return str_replace($words, $replace, $string);
    }

    //==replace white space=============//
    public static function removeSpace(String $string): String
    {
        return str_replace('spkahfst', ' ', $string);
    }

    public static function plusSign(String $string): String
    {
        return str_replace('+', 'PLUSSIGN', $string);
    }

    public static function removePlusSign(String $string): String
    {
        return str_replace('PLUSSIGN', '+', $string);
    }

    //=========encrypt data=============
    public static function encryption($strings) {
        $sSalt = substr(hash('sha256', Config::get("ENCRYPTION_KEY"), true), 0, 32);
        $method = 'aes-256-cbc';
        $iv = base64_decode("DB4gHxkcBQkKCxoRGBkaFA==");
        $encrypted = openssl_encrypt($strings, $method, $sSalt, 0, $iv);
        return $encrypted;
    }

    //====decrypt data===============
    public static function decryption($string) {
        $sSalt = substr(hash('sha256', Config::get("ENCRYPTION_KEY"), true), 0, 32);
        $method = 'aes-256-cbc';
        $iv = base64_decode("DB4gHxkcBQkKCxoRGBkaFA==");
        $decrypted = openssl_decrypt($string, $method, $sSalt, 0, $iv);
        return $decrypted;
    }


    public static function fileEncryption(String $encKey, String $encIV, String $inPath, String $outPath): bool
    {
        $sourceFile = file_get_contents($inPath);
        $key = base64_decode($encKey);
        $iv = base64_decode($encIV);
        $path_parts = pathinfo($inPath);
        $fileName = $path_parts['filename'];
        $outFile = $outPath . $fileName . '.pbm';
        $encrypter = 'aes-256-cbc';
        $encryptedString = openssl_encrypt($sourceFile, $encrypter, $key, 0, $iv);
        if (file_put_contents($outFile, $encryptedString) != false) {
            return true;
        } else {
            return false;
        }
    }
    public static function fileDecryption(String $encKey, String $encIV, String $inPath, String $outPath): bool
    {
        $encryptedString = file_get_contents($inPath);
        $key = base64_decode($encKey);
        $iv = base64_decode($encIV);
        $path_parts = pathinfo($inPath);
        $fileName = $path_parts['filename'];
        $outFile = $outPath . $fileName . '.mp4';
        $encrypter = 'aes-256-cbc';
        $decrypted = openssl_decrypt($encryptedString, $encrypter, $key, 0, $iv);
        if (file_put_contents($outFile, $decrypted) != false) {
            return true;
        } else {
            return false;
        }
    }

    //=====validate user ip address========
    public static function isValidIpAddress($ipAddress)
    {
        if (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 || FILTER_FLAG_IPV6 || FILTER_FLAG_NO_PRIV_RANGE || FILTER_FLAG_NO_RES_RANGE) === false) {
            return false;
        }
        return $ipAddress;
    }
}
