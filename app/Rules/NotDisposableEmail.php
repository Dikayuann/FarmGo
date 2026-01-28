<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NotDisposableEmail implements Rule
{
    /**
     * List of known disposable/temporary email domains
     * 
     * @var array
     */
    protected $disposableDomains = [
        // Popular temporary email services
        'tempmail.com',
        'temp-mail.org',
        'guerrillamail.com',
        'guerrillamail.net',
        'guerrillamail.org',
        'guerrillamail.biz',
        'guerrillamail.de',
        '10minutemail.com',
        '10minutemail.net',
        'mailinator.com',
        'throwaway.email',
        'trashmail.com',
        'getnada.com',
        'maildrop.cc',
        'temp-mail.io',
        'yopmail.com',
        'yopmail.fr',
        'yopmail.net',
        'cool.fr.nf',
        'jetable.fr.nf',
        'nospam.ze.tc',
        'nomail.xl.cx',
        'mega.zik.dj',
        'speed.1s.fr',
        'courriel.fr.nf',
        'moncourrier.fr.nf',
        'monemail.fr.nf',
        'monmail.fr.nf',
        'hide.biz.st',
        'mymail.infos.st',

        // Additional disposable domains
        'fakeinbox.com',
        'emailondeck.com',
        'sharklasers.com',
        'grr.la',
        'guerrillamailblock.com',
        'pokemail.net',
        'spam4.me',
        'tempail.com',
        'tempemail.net',
        'throwawaymail.com',
        'trashmail.net',
        'wegwerfmail.de',
        'wegwerfmail.net',
        'wegwerfmail.org',
        'mintemail.com',
        'mytemp.email',
        'mohmal.com',
        'emailfake.com',
        'discard.email',
        'discardmail.com',
        'discardmail.de',
        'spambog.com',
        'spambog.de',
        'spambog.ru',
        'mailnesia.com',
        'mailcatch.com',
        'mailexpire.com',
        'mailforspam.com',
        'mailfreeonline.com',
        'mailmetrash.com',
        'mailtothis.com',
        'mailinator2.com',
        'mailinator.net',
        'mailinator.org',
    ];

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Extract domain from email
        $domain = strtolower(substr(strrchr($value, "@"), 1));

        // Check if domain is in disposable list
        if (in_array($domain, $this->disposableDomains)) {
            return false;
        }

        // Check if domain has valid MX records (real email server)
        // This will block fake domains like "conax2122@myfhk.com"
        if (!$this->hasValidMxRecords($domain)) {
            return false;
        }

        return true;
    }

    /**
     * Check if domain has valid MX records
     *
     * @param  string  $domain
     * @return bool
     */
    protected function hasValidMxRecords($domain)
    {
        // Check for MX records
        $mxRecords = [];
        $hasMx = @getmxrr($domain, $mxRecords);

        if ($hasMx && count($mxRecords) > 0) {
            return true;
        }

        // If no MX records, check for A record (some domains use A record for mail)
        $aRecord = @dns_get_record($domain, DNS_A);
        if ($aRecord && count($aRecord) > 0) {
            return true;
        }

        // No valid DNS records found
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Email tidak valid atau domain tidak terdaftar. Silakan gunakan email yang valid.';
    }
}
