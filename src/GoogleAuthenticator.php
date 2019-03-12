<?php

namespace Dolondro\GoogleAuthenticator;

use Base32\Base32;
use Psr\Cache\CacheItemPoolInterface;
use Psr\SimpleCache\CacheInterface;

class GoogleAuthenticator
{
    // According to the spec, this could be something other than 6. But again, apparently Google Authenticator ignores
    // that part of the spec...
    protected $codeLength = 6;

    /**
     * @var CacheItemPoolInterface|null
     */
    protected $cache = null;

    protected $options = [
        "window" => 1,
        "time" => null,
    ];

    public function __construct($options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * @param CacheItemPoolInterface|CacheInterface $cache
     *
     * @throws \Exception
     */
    public function setCache($cache)
    {
        if ($cache instanceof CacheItemPoolInterface || $cache instanceof CacheInterface) {
            $this->cache = $cache;

            return;
        }

        throw new \Exception("Cache is not PSR-16 or PSR-6 compliant");
    }

    /**
     * @param string $secret
     * @param string $code
     *
     * @return bool
     *
     * @throws \Exception
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function authenticate($secret, $code)
    {
        $correct = false;
        $time = isset($this->options["time"]) ? $this->options["time"] : time();

        $window = $this->options["window"];

        for ($i = -$window; $i <= $window; $i++) {
            $timeSlice = $this->getTimeSlice($time, $i);

            //if ($this->isEqual)
            if ($this->isEqual($this->calculateCode($secret, $timeSlice), $code)) {
                $correct = true;

                break;
            }
        }

        // If they don't have a cache, then we return whatever we've got so far!
        if (is_null($this->cache)) {
            return $correct;
        }

        // Equally, if they were wrong, we also want to return
        if (!$correct) {
            return $correct;
        }

        // If we're here then we must be using a cache, and we must be right

        // We generate the key as securely as possible, then salt it using something that will always be replicable.
        // We're doing this hashing for de-duplication (aka, we want to know if it exists), but as we're also possibly
        // securing the secret somewhere, we want to try and have as secure as possible
        //
        // Annoyingly, crypt looks like it can return characters outside of the range of acceptable keys, so we're just
        // md5'ing again to make the characters acceptable :P
        // There definitely will be a better way of doing this, but this is a quick bugfix
        //
        // If someone has any better suggestions on how to achieve this, please send in a PR! :P
        $key = md5(crypt($secret."|".$code, md5($code)));

        // People mostly use PSR-16 these days as PSR-6 was a PITA
        if ($this->cache instanceof CacheInterface) {
            if ($this->cache->has($key)) {
                return false;
            }

            $this->cache->set($key, true, 30);

            return true;
        }

        if ($this->cache instanceof CacheItemPoolInterface) {
            if ($this->cache->hasItem($key)) {
                return false;
            }

            // If it didn't, then we want this function to add it to the cache
            // In PSR-6 getItem will always contain an CacheItemInterface and that seems to be the only way to add stuff
            // to the cachePool
            $item = $this->cache->getItem($key);
            // It's a quick expiry thing, 30 seconds is more than long enough
            $item->expiresAfter(30);
            // We don't care about the value at all, it's just something that's needed to use the caching interface
            $item->set(true);
            $this->cache->save($item);

            return true;
        }

        // I'd be pretty impressed if someone got here
        return true;
    }

    /**
     * @param int $time
     * @param int $offset
     *
     * @return float|int
     */
    protected function getTimeSlice($time, $offset = 0)
    {
        return floor($time / 30) + $offset;
    }

    /**
     * @param $string1
     * @param $string2
     *
     * @return bool
     */
    protected function isEqual($string1, $string2)
    {
        return substr_count($string1 ^ $string2, "\0") * 2 === strlen($string1.$string2);
    }

    /**
     * @param string   $secret
     * @param int|null $timeSlice
     *
     * @return string
     */
    public function calculateCode($secret, $timeSlice = null)
    {
        // If we haven't been fed a timeSlice, then get one.
        // It looks a bit unclean doing it like this, but it allows us to write testable code
        $time = isset($this->options["time"]) ? $this->options["time"] : time();
        $timeSlice = $timeSlice ? $timeSlice : $this->getTimeSlice($time);

        // Packs the timeslice as a "unsigned long" (always 32 bit, big endian byte order)
        $timeSlice = pack("N", $timeSlice);

        // Then pad it with the null terminator
        $timeSlice = str_pad($timeSlice, 8, chr(0), STR_PAD_LEFT);

        // Hash it with SHA1. The spec does offer the idea of other algorithms, but notes that the authenticator is currently
        // ignoring it...
        $hash = hash_hmac("SHA1", $timeSlice, Base32::decode($secret), true);

        // Last 4 bits are an offset apparently
        $offset = ord(substr($hash, -1)) & 0x0F;

        // Grab the last 4 bytes
        $result = substr($hash, $offset, 4);

        // Unpack it again
        $value = unpack('N', $result)[1];

        // Only 32 bits
        $value = $value & 0x7FFFFFFF;

        // Modulo down to the right number of digits
        $modulo = pow(10, $this->codeLength);

        // Finally, pad out the string with 0s
        return str_pad($value % $modulo, $this->codeLength, '0', STR_PAD_LEFT);
    }
}
