<?php

declare(strict_types=1);

namespace Imgproxy;

class Url
{
    /**
     * @var string
     */
    private $imageUrl;
    /**
     * @var int
     */
    private $w;
    /**
     * @var int
     */
    private $h;
    /**
     * @var string
     */
    private $fit = "fit";
    /**
     * @var string
     */
    private $gravity = "sm";
    /**
     * @var bool
     */
    private $enlarge = false;
    /**
     * @var string|null
     */
    private $extension = null;
    /**
     * @var UrlBuilder
     */
    private $builder;

    /**
     * Url constructor.
     * @param string $imageUrl
     * @param int $w
     * @param int $h
     */
    public function __construct(UrlBuilder $builder, string $imageUrl, int $w, int $h)
    {
        $this->builder = $builder;
        $this->imageUrl = $imageUrl;
        $this->w = $w;
        $this->h = $h;
    }

    public function unsignedPath(): string
    {
        $enlarge = (string)(int)$this->enlarge;
        $encodedUrl = rtrim(strtr(base64_encode($this->imageUrl), '+/', '-_'), '=');
        $ext = $this->extension ?: pathinfo($this->imageUrl, PATHINFO_EXTENSION);
        return "/{$this->fit}/{$this->w}/{$this->h}/{$this->gravity}/{$enlarge}/{$encodedUrl}" . ($ext ? ".$ext" : "");
    }

    public function insecureSignedPath(): string
    {
        $unsignedPath = $this->unsignedPath();
        return "/insecure$unsignedPath";
    }

    public function secureSignedPath(): string
    {
        $unsignedPath = $this->unsignedPath();
        $data = $this->builder->getSalt() . $unsignedPath;
        $sha256 = hash_hmac('sha256', $data, $this->builder->getKey(), true);
        $sha256Encoded = base64_encode($sha256);
        $signature = str_replace(["+", "/", "="], ["-", "_", ""], $sha256Encoded);;
        return "/{$signature}{$unsignedPath}";
    }

    public function signedPath(): string
    {
        if (!$this->builder->isInsecure()) {
            return $this->secureSignedPath();
        }

        return $this->insecureSignedPath();
    }

    public function toString(): string
    {
        return $this->builder->getBaseUrl() . $this->signedPath();
    }

    /**
     * @param int $w
     * @return $this
     */
    public function setWidth(int $w): Url
    {
        $this->w = $w;
        return $this;
    }

    /**
     * @param int $h
     * @return $this
     */
    public function setHeight(int $h): Url
    {
        $this->h = $h;
        return $this;
    }

    /**
     * @param string $fit
     * @return $this
     */
    public function setFit(string $fit): Url
    {
        $this->fit = $fit;
        return $this;
    }

    /**
     * @param string $gravity
     * @return $this
     */
    public function setGravity(string $gravity): Url
    {
        $this->gravity = $gravity;
        return $this;
    }

    /**
     * @param bool $enlarge
     * @return $this
     */
    public function setEnlarge(bool $enlarge): Url
    {
        $this->enlarge = $enlarge;
        return $this;
    }

    /**
     * @param string|null $extension
     * @return $this
     */
    public function setExtension(?string $extension): Url
    {
        $this->extension = $extension;
        return $this;
    }
}