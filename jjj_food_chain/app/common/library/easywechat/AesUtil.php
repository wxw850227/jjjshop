<?php
/**
 * AesUtil
 * PHP version 5
 *
 * @category Class
 * @package  WechatPay
 * @author   WeChatPay Team
 * @link     https://pay.weixin.qq.com
 */


namespace app\common\library\easywechat;

/**
 * Util for AEAD_AES_256_GCM.
 *
 * @package  WechatPay
 * @author   WeChatPay Team
 */
class AesUtil
{
    /**
     * AES key
     *
     * @var string
     */
    private $aesKey;

    const KEY_LENGTH_BYTE = 32;
    const AUTH_TAG_LENGTH_BYTE = 16;

    /**
     * Constructor
     *
     * @param string $aesKey API V3 Key
     *
     */
    public function __construct($aesKey)
    {
        if (strlen($aesKey) != self::KEY_LENGTH_BYTE) {
            throw new \InvalidArgumentException('无效的ApiV3Key，长度应为32个字节');
        }
        $this->aesKey = $aesKey;
    }

    /**
     * Decrypt AEAD_AES_256_GCM ciphertext
     *
     * @param string $associatedData AES GCM additional authentication data
     * @param string $nonceStr AES GCM nonce
     * @param string $ciphertext AES GCM cipher text
     *
     * @return string|bool      Decrypted string on success or FALSE on failure
     */
    public function decryptToString($associatedData, $nonceStr, $ciphertext)
    {
        $ciphertext = \base64_decode($ciphertext);
        if (strlen($ciphertext) <= self::AUTH_TAG_LENGTH_BYTE) {
            return false;
        }

        // ext-sodium (default installed on >= PHP 7.2)
        if (function_exists('\sodium_crypto_aead_aes256gcm_is_available') &&
            \sodium_crypto_aead_aes256gcm_is_available()) {
            return \sodium_crypto_aead_aes256gcm_decrypt($ciphertext, $associatedData, $nonceStr, $this->aesKey);
        }

        // ext-libsodium (need install libsodium-php 1.x via pecl)
        if (function_exists('\Sodium\crypto_aead_aes256gcm_is_available') &&
            \Sodium\crypto_aead_aes256gcm_is_available()) {
            return \Sodium\crypto_aead_aes256gcm_decrypt($ciphertext, $associatedData, $nonceStr, $this->aesKey);
        }

        // openssl (PHP >= 7.1 support AEAD)
        if (PHP_VERSION_ID >= 70100 && in_array('aes-256-gcm', \openssl_get_cipher_methods())) {
            $ctext = substr($ciphertext, 0, -self::AUTH_TAG_LENGTH_BYTE);
            $authTag = substr($ciphertext, -self::AUTH_TAG_LENGTH_BYTE);

            return \openssl_decrypt($ctext, 'aes-256-gcm', $this->aesKey, \OPENSSL_RAW_DATA, $nonceStr,
                $authTag, $associatedData);
        }

        throw new \RuntimeException('AEAD_AES_256_GCM需要PHP 7.1以上或者安装libsodium-php');
    }
}
