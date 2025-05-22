<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class EncryptionHelper
{
    /**
     * Encrypt sensitive data
     *
     * @param mixed $value
     * @return string|null
     */
    public static function encrypt($value)
    {
        if (empty($value)) {
            return null;
        }

        return Crypt::encryptString($value);
    }

    /**
     * Decrypt sensitive data
     *
     * @param string|null $encryptedValue
     * @return mixed|null
     */
    public static function decrypt($encryptedValue)
    {
        if (empty($encryptedValue)) {
            return null;
        }

        try {
            return Crypt::decryptString($encryptedValue);
        } catch (DecryptException $e) {
            \Log::error('Decryption error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Mask sensitive data (e.g., credit card numbers, customer info)
     *
     * @param string $value
     * @param int $visibleChars Number of characters to leave visible at the end
     * @return string
     */
    public static function mask($value, $visibleChars = 4)
    {
        if (empty($value)) {
            return '';
        }

        $length = strlen($value);
        
        if ($length <= $visibleChars) {
            return $value;
        }

        $maskedPortion = str_repeat('*', $length - $visibleChars);
        $visiblePortion = substr($value, -$visibleChars);
        
        return $maskedPortion . $visiblePortion;
    }
}
