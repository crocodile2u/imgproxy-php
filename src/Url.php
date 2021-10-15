<?php

declare(strict_types=1);

namespace Imgproxy;

class Url
{
    public const MODE_LEGACY = 'legacy';
    public const MODE_ADVANCED = 'advanced';
    /**
     * @var string
     */
    private $imageUrl;

    /**
     * @var OptionSet
     */
    private $options;
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

    private $mode = self::MODE_LEGACY;

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
        $this->options = new OptionSet();
        $this->setWidth($w);
        $this->setHeight($h);
    }

    public function useLegacyMode(): self
    {
        $this->mode = self::MODE_LEGACY;
        return $this;
    }

    public function useAdvancedMode(): self
    {
        $this->mode = self::MODE_ADVANCED;
        return $this;
    }

    public function options(): OptionSet
    {
        return $this->options;
    }

    public function unsignedPath(): string
    {
        switch ($this->mode) {
            case self::MODE_LEGACY:
                return $this->unsignedPathLegacy();
            case self::MODE_ADVANCED:
                return $this->unsignedPathAdvanced();
            default:
                throw new \LogicException("unknown URL mode");
        }

    }

    public function insecureSignedPath(string $unsignedPath): string
    {
        return "/insecure$unsignedPath";
    }

    public function secureSignedPath(string $unsignedPath): string
    {
        $data = $this->builder->getSalt() . $unsignedPath;
        $sha256 = hash_hmac('sha256', $data, $this->builder->getKey(), true);
        if ($this->builder->getSignatureSize() > 0) {
            $sha256 = substr($sha256, 0, $this->builder->getSignatureSize());
        }
        $sha256Encoded = base64_encode($sha256);
        $signature = str_replace(["+", "/", "="], ["-", "_", ""], $sha256Encoded);;
        return "/{$signature}{$unsignedPath}";
    }

    public function signedPath(): string
    {
        $unsignedPath = $this->unsignedPath();
        $result = $this->builder->isSecure() ? $this->secureSignedPath($unsignedPath) : $this->insecureSignedPath($unsignedPath);
        return $result;
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
        $this->options->withWidth($w);
        return $this;
    }

    /**
     * @param int $h
     * @return $this
     */
    public function setHeight(int $h): Url
    {
        $this->options->withHeight($h);
        return $this;
    }

    /**
     * @param string $fit
     * @return $this
     */
    public function setFit(string $fit): Url
    {
        $this->fit = $fit;
        $this->options->withResizingType($fit);
        return $this;
    }

    /**
     * @param string $gravity
     * @return $this
     */
    public function setGravity(string $gravity): Url
    {
        $this->gravity = $gravity;
        $this->options->withGravity($gravity);
        return $this;
    }

    /**
     * @param bool $enlarge
     * @return $this
     */
    public function setEnlarge(bool $enlarge): Url
    {
        $this->enlarge = $enlarge;
        $enlarge ? $this->options->withEnlarge() : $this->options->unset(ProcessingOption::ENLARGE);
        return $this;
    }

    /**
     * @param string|null $extension
     * @return $this
     */
    public function setExtension(?string $extension): Url
    {
        $this->extension = $extension;
        $extension ? $this->options->withFormat($extension) : $this->options->unset(ProcessingOption::FORMAT);
        return $this;
    }

    /**
     * @return mixed
     */
    protected function resolveExtension(): string
    {
        if ("local://" === substr($this->imageUrl, 0, 8)) {
            $path = substr($this->imageUrl, 8);
        } else {
            $path = parse_url($this->imageUrl, PHP_URL_PATH);
        }

        $ext = $path ? strtolower(pathinfo($path, PATHINFO_EXTENSION)) : "";
        return $ext ?: "";
    }

    /**
     * @return string
     */
    private function unsignedPathLegacy(): string
    {
        $enlarge = (string)(int)$this->enlarge;
        $encodedUrl = rtrim(strtr(base64_encode($this->imageUrl), '+/', '-_'), '=');
        $w = $this->options->width();
        $h = $this->options->height();
        $path = "/{$this->fit}/{$w}/{$h}/{$this->gravity}/{$enlarge}/{$encodedUrl}";
        return $this->appendExtension($path);
    }

    private function unsignedPathAdvanced()
    {
        $encodedUrl = rtrim(strtr(base64_encode($this->imageUrl), '+/', '-_'), '=');
        $path = "/{$this->options->toString()}/{$encodedUrl}";
        return $this->appendExtension($path);
    }

    private function appendExtension(string $path): string
    {
        $ext = $this->extension ?: $this->resolveExtension();
        return $path . ($ext ? ".$ext" : "");
    }
}