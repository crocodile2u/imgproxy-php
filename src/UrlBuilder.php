<?php

declare(strict_types=1);

namespace Imgproxy;

class UrlBuilder
{
    /**
     * @var string
     */
    private $baseUrl;
    /**
     * @var string
     */
    private $salt;
    /**
     * @var string
     */
    private $key;

    /**
     * @var bool
     */
    private $secure = false;

    /**
     * UrlBuilder constructor.
     * @param string $baseUrl
     * @param string $key
     * @param string $salt
     * @throws Exception
     */
    public function __construct(string $baseUrl, string $key = null, string $salt = null)
    {
        if ($key && $salt) {
            $this->key = pack("H*" , $key) ?: $this->throwException("Key expected to be hex-encoded string");
            $this->salt = pack("H*" , $salt) ?: $this->throwException("Salt expected to be hex-encoded string");
            $this->secure = true;
        }

        $this->baseUrl = $baseUrl;
    }

    public function build(
        string $imageUrl,
        int $w,
        int $h,
        string $fit = "fit",
        string $gravity = "sm",
        bool $enlarge = false,
        string $extension = null
    ): Url {
        return (new Url($this, $imageUrl, $w, $h))
            ->setFit($fit)
            ->setGravity($gravity)
            ->setEnlarge($enlarge)
            ->setExtension($extension);
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @return bool
     */
    public function getSalt(): string
    {
        return $this->salt;
    }

    /**
     * @return bool
     */
    public function getKey(): string
    {
        return $this->key;
    }

    public function isSecure(): bool
    {
        return $this->secure;
    }

    /**
     * @param string $message
     * @throws Exception
     */
    private function throwException(string $message)
    {
        throw new Exception($message);
    }
}