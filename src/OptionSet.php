<?php

declare(strict_types=1);

namespace Imgproxy;

class OptionSet
{
    /**
     * @var array
     */
    private $options = [];

    private function set(string $name, ...$args): self
    {
        $this->options[$name] = $args;
        return $this;
    }

    private function unset(string $name): self
    {
        unset($this->options[$name]);
        return $this;
    }

    private function get(string $name): ?array
    {
        return $this->options[$name] ?? null;
    }

    public function toString(): string
    {
        $parts = [];
        foreach ($this->options as $name => $args) {
            $nameAndArgs = array_merge([$name], $args);
            return join(":", $nameAndArgs);// not using [$name, ...$args] to keep it 7.2 compatible
        }
        return join("/", $parts);
    }

    public function withWidth(int $w): self
    {
        if ($w < 0) {
            throw new \InvalidArgumentException("width must be >= 0");
        }
        return $this->set(ProcessingOption::WIDTH, $w);
    }

    public function withoutWidth(): self
    {
        return $this->unset(ProcessingOption::WIDTH);
    }

    public function width(): ?int
    {
        return $this->firstValue(ProcessingOption::WIDTH, 'int');
    }

    public function withHeight(int $h): self
    {
        if ($h < 0) {
            throw new \InvalidArgumentException("height must be >= 0");
        }
        return $this->set(ProcessingOption::HEIGHT, $h);
    }

    public function withoutHeight(): self
    {
        return $this->unset(ProcessingOption::HEIGHT);
    }

    public function height(): ?int
    {
        return $this->firstValue(ProcessingOption::HEIGHT, 'int');
    }

    public function withResizingType(string $rt): self
    {
        switch ($rt) {
            case ResizingType::FIT:
            case ResizingType::FILL:
            case ResizingType::AUTO:
                return $this->set(ProcessingOption::RESIZING_TYPE, $rt);
            default:
                throw new \InvalidArgumentException("unknown resizing type $rt");
        }
    }

    public function withoutResizingType(): self
    {
        return $this->unset(ProcessingOption::RESIZING_TYPE);
    }

    public function resizingType(): ?string
    {
        return $this->firstValue(ProcessingOption::RESIZING_TYPE, 'string');
    }

    public function withResizingAlgorithm(string $ra): self
    {
        switch ($ra) {
            case ResizingAlgorithm::LANCZOS3:
            case ResizingAlgorithm::LANCZOS2:
            case ResizingAlgorithm::NEAREST:
            case ResizingAlgorithm::CUBIC:
            case ResizingAlgorithm::LINEAR:
                return $this->set(ProcessingOption::RESIZING_ALGORITHM, $ra);
            default:
                throw new \InvalidArgumentException("unknown resizing algorithm $ra");
        }
    }

    public function withoutResizingAlgorithm(): self
    {
        return $this->unset(ProcessingOption::RESIZING_ALGORITHM);
    }

    public function resizingAlgorithm(): ?string
    {
        return $this->firstValue(ProcessingOption::RESIZING_ALGORITHM, 'string');
    }

    public function withDpr(int $v): self
    {
        if ($v <= 0) {
            throw new \InvalidArgumentException("dpr must be greater than 0");
        }

        if ($v === 1) {
            return $this->withoutDpr();
        }

        return $this->set(ProcessingOption::DPR, $v);
    }

    public function withoutDpr(): self
    {
        return $this->unset(ProcessingOption::DPR);
    }

    public function dpr(): ?int
    {
        return $this->firstValue(ProcessingOption::DPR, 'int');
    }

    public function withEnlarge(): self
    {
        return $this->set(ProcessingOption::ENLARGE, 1);
    }

    public function withoutEnlarge(): self
    {
        return $this->unset(ProcessingOption::ENLARGE);
    }

    public function enlarge(): bool
    {
        return $this->firstValue(ProcessingOption::ENLARGE, 'bool');
    }

    public function withExtend(string $gravityType = null, $gravityX = null, $gravityY = null): self
    {
        switch ($gravityType) {
            case null:
                return $this->set(ProcessingOption::EXTEND, 1);
            case Gravity::NORTH:
            case Gravity::SOUTH:
            case Gravity::EAST:
            case Gravity::WEST:
            case Gravity::NORTH_EAST:
            case Gravity::NORTH_WEST:
            case Gravity::SOUTH_EAST:
            case Gravity::SOUTH_WEST:
            case Gravity::CENTER:
                $gravityX = (int)$gravityX;
                $gravityY = (int)$gravityY;
                return $this->set(ProcessingOption::EXTEND, 1, $gravityType, $gravityX, $gravityY);
            case Gravity::FOCUS_POINT:
                $gravityX = (float)$gravityX;
                $gravityY = (float)$gravityY;
                return $this->set(ProcessingOption::EXTEND, 1, $gravityType, $gravityX, $gravityY);
            default:
                throw new \InvalidArgumentException("unexpected gravity type for 'extend': $gravityType");
        }
    }

    public function withoutExtend(): self
    {
        return $this->unset(ProcessingOption::EXTEND);
    }

    public function extend(): ?array
    {
        return $this->get(ProcessingOption::EXTEND);
    }

    public function withGravity(string $type = null, $x = null, $y = null): self
    {
        switch ($type) {
            case Gravity::SMART:
                return $this->set(ProcessingOption::GRAVITY, $type);
            case Gravity::NORTH:
            case Gravity::SOUTH:
            case Gravity::EAST:
            case Gravity::WEST:
            case Gravity::NORTH_EAST:
            case Gravity::NORTH_WEST:
            case Gravity::SOUTH_EAST:
            case Gravity::SOUTH_WEST:
            case Gravity::CENTER:
                $x = (int)$x;
                $y = (int)$y;
                return $this->set(ProcessingOption::GRAVITY, $type, $x, $y);
            case Gravity::FOCUS_POINT:
                $x = (float)$x;
                $y = (float)$y;
                return $this->set(ProcessingOption::GRAVITY, $type, $x, $y);
            default:
                throw new \InvalidArgumentException("unexpected gravity type $type");
        }
    }

    public function withoutGravity(): self
    {
        return $this->unset(ProcessingOption::GRAVITY);
    }

    public function gravity(): ?array
    {
        return $this->get(ProcessingOption::GRAVITY);
    }

    public function withCrop($w, $h, string $gravityType = null, $gravityX = null, $gravityY = null): self
    {
        switch ($gravityType) {
            case null:
                return $this->set(ProcessingOption::CROP, $w, $h);
            case Gravity::SMART:
                return $this->set(ProcessingOption::CROP, $w, $h, $gravityType);
            case Gravity::NORTH:
            case Gravity::SOUTH:
            case Gravity::EAST:
            case Gravity::WEST:
            case Gravity::NORTH_EAST:
            case Gravity::NORTH_WEST:
            case Gravity::SOUTH_EAST:
            case Gravity::SOUTH_WEST:
            case Gravity::CENTER:
                $gravityX = (int)$gravityX;
                $gravityY = (int)$gravityY;
                return $this->set(ProcessingOption::CROP, $w, $h, $gravityType, $gravityX, $gravityY);
            case Gravity::FOCUS_POINT:
                $gravityX = (float)$gravityX;
                $gravityY = (float)$gravityY;
                return $this->set(ProcessingOption::CROP, $w, $h, $gravityType, $gravityX, $gravityY);
            default:
                throw new \InvalidArgumentException("unexpected gravity type for 'extend': $gravityType");
        }
    }

    public function withoutCrop(): self
    {
        return $this->unset(ProcessingOption::CROP);
    }

    public function crop(): ?array
    {
        return $this->get(ProcessingOption::CROP);
    }

    public function withPadding(int $t, int $r, int $b, int $l): self
    {
        if ($t < 0) {
            throw new \InvalidArgumentException("top padding must be >= 0");
        }
        if ($r < 0) {
            throw new \InvalidArgumentException("right padding must be >= 0");
        }
        if ($b < 0) {
            throw new \InvalidArgumentException("bottom padding must be >= 0");
        }
        if ($l < 0) {
            throw new \InvalidArgumentException("left padding must be >= 0");
        }
        if (($t === 0) && ($r === 0) && ($b === 0) && ($l === 0)) {
            throw new \InvalidArgumentException("at least one padding must be > 0");
        }
        return $this->set(ProcessingOption::PADDING, $t, $r, $b, $l);
    }

    public function withoutPadding(): self
    {
        return $this->unset(ProcessingOption::PADDING);
    }

    public function padding(): ?array
    {
        return $this->get(ProcessingOption::PADDING);
    }

    public function withTrim(int $threshold, string $color = "", bool $equalHor = false, bool $equalVer = false): self
    {
        return $this->set(ProcessingOption::TRIM, $threshold, $color, (int)$equalHor, (int)$equalVer);
    }

    public function withoutTrim(): self
    {
        return $this->unset(ProcessingOption::TRIM);
    }

    public function trim(): ?array
    {
        return $this->get(ProcessingOption::TRIM);
    }

    public function withRotate(int $angle): self
    {
        switch ($angle) {
            case Rotate::CLOCKWISE:
            case Rotate::COUNTERCLOCKWISE:
            case Rotate::UPSIDE_DOWN:
                return $this->set(ProcessingOption::ROTATE, $angle);
            case Rotate::NONE:
                return $this->withoutRotate();
            default:
                throw new \InvalidArgumentException("only 0, 90, 180, 270 degrees rotation is supported");
        }
    }

    public function withoutRotate(): self
    {
        return $this->unset(ProcessingOption::ROTATE);
    }

    public function rotate(): ?int
    {
        return $this->firstValue(ProcessingOption::ROTATE, 'int');
    }

    public function withMaxBytes(int $bytes): self
    {
        if ($bytes <= 0) {
            throw new \InvalidArgumentException("max_bytes must be greater than 0");
        }
        return $this->set(ProcessingOption::MAX_BYTES, $bytes);
    }

    public function withoutMaxBytes(): self
    {
        return $this->unset(ProcessingOption::MAX_BYTES);
    }

    public function maxBytes(): ?int
    {
        return $this->firstValue(ProcessingOption::MAX_BYTES, 'int');
    }

    public function withBackgroundRGB(int $r, int $g, int $b): self
    {
        if ($r < 0) {
            throw new \InvalidArgumentException("RGB color Red component must be >= 0");
        }
        if ($r > 255) {
            throw new \InvalidArgumentException("RGB color Red component must be <= 255");
        }
        if ($g < 0) {
            throw new \InvalidArgumentException("RGB color Green component must be >= 0");
        }
        if ($g > 255) {
            throw new \InvalidArgumentException("RGB color Green component must be <= 255");
        }
        if ($b < 0) {
            throw new \InvalidArgumentException("RGB color Blue component must be >= 0");
        }
        if ($b > 255) {
            throw new \InvalidArgumentException("RGB color Blue component must be <= 255");
        }
        return $this->set(ProcessingOption::BACKGROUND, $r, $g, $b);
    }

    public function withBackgroundHex(string $hexColor): self
    {
        if (strlen($hexColor) != 6) {
            throw new \InvalidArgumentException("HEX color must be a string of 6 chars");
        }
        return $this->set(ProcessingOption::BACKGROUND, $hexColor);
    }

    public function withoutBackground(): self
    {
        return $this->unset(ProcessingOption::BACKGROUND);
    }

    public function background(): ?array
    {
        return $this->get(ProcessingOption::BACKGROUND);
    }

    public function withBackgroundAlpha(float $alpha): self
    {
        if (($alpha < 0) || ($alpha > 1)) {
            throw new \InvalidArgumentException("background_alpha must be between 0 and 1");
        }
        return $this->set(ProcessingOption::BACKGROUND_ALPHA, $alpha);
    }

    public function withoutBackgroundAlpha(): self
    {
        return $this->unset(ProcessingOption::BACKGROUND_ALPHA);
    }

    public function backgroundAlpha(): ?float
    {
        return $this->firstValue(ProcessingOption::BACKGROUND_ALPHA, 'float');
    }

    public function withBrightness(int $v): self
    {
        if (($v < -255) || ($v > 255)) {
            throw new \InvalidArgumentException("brightness must be between -255 and 255");
        }
        return $this->set(ProcessingOption::BRIGHTNESS, $v);
    }

    public function withoutBrightness(): self
    {
        return $this->unset(ProcessingOption::BRIGHTNESS);
    }

    public function brightness(): ?int
    {
        return $this->firstValue(ProcessingOption::BRIGHTNESS, 'int');
    }

    public function withContrast(float $v): self
    {
        if (($v < 0) || ($v > 1)) {
            throw new \InvalidArgumentException("contrast must be between 0 and 1");
        }
        return $this->set(ProcessingOption::CONTRAST, $v);
    }

    public function withoutContrast(): self
    {
        return $this->unset(ProcessingOption::CONTRAST);
    }

    public function contrast(): ?float
    {
        return $this->firstValue(ProcessingOption::CONTRAST, 'float');
    }

    public function withSaturation(float $v): self
    {
        if (($v < 0) || ($v > 1)) {
            throw new \InvalidArgumentException("saturation must be between 0 and 1");
        }
        return $this->set(ProcessingOption::SATURATION, $v);
    }

    public function withoutSaturation(): self
    {
        return $this->unset(ProcessingOption::SATURATION);
    }

    public function saturation(): ?float
    {
        return $this->firstValue(ProcessingOption::SATURATION, 'float');
    }

    public function withBlur(float $sigma): self
    {
        if ($sigma <= 0) {
            throw new \InvalidArgumentException("sigma must be greater than 0");
        }
        return $this->set(ProcessingOption::BLUR, $sigma);
    }

    public function withoutBlur(): self
    {
        return $this->unset(ProcessingOption::BLUR);
    }

    public function blur(): ?float
    {
        return $this->firstValue(ProcessingOption::BLUR, 'float');
    }

    public function withSharpen(float $sigma): self
    {
        if ($sigma <= 0) {
            throw new \InvalidArgumentException("sigma must be greater than 0");
        }
        return $this->set(ProcessingOption::SHARPEN, $sigma);
    }

    public function withoutSharpen(): self
    {
        return $this->unset(ProcessingOption::SHARPEN);
    }

    public function sharpen(): ?float
    {
        return $this->firstValue(ProcessingOption::SHARPEN, 'float');
    }

    public function withPixelate(int $size): self
    {
        if ($size <= 0) {
            throw new \InvalidArgumentException("size must be greater than 0");
        }
        return $this->set(ProcessingOption::PIXELATE, $size);
    }

    public function withoutPixelate(): self
    {
        return $this->unset(ProcessingOption::PIXELATE);
    }

    public function pixelate(): ?int
    {
        return $this->firstValue(ProcessingOption::PIXELATE, 'int');
    }

    public function withUnsharpening(
        string $mode = UnsharpeningMode::AUTO,
        float $weight = null,
        float $dividor = null
    ): self {
        switch ($mode) {
            case UnsharpeningMode::AUTO:
            case UnsharpeningMode::NONE:
            case UnsharpeningMode::ALWAYS:
                break;
            default:
                throw new \InvalidArgumentException("unknown unsharpening mode $mode");
        }
        if ($weight === null) {
            $weight = 1;
        }
        if ($dividor === null) {
            $weight = 24;// default value according to https://docs.imgproxy.net/configuration?id=unsharpening
        }
        if ($weight <= 0) {
            throw new \InvalidArgumentException("weight must be greater than 0");
        }
        if ($dividor <= 0) {
            throw new \InvalidArgumentException("dividor must be greater than 0");
        }
        return $this->set(ProcessingOption::UNSHARPENING, $mode, $weight, $dividor);
    }

    public function withoutUnsharpening(): self
    {
        return $this->unset(ProcessingOption::UNSHARPENING);
    }

    public function unsharpening(): ?array
    {
        return $this->get(ProcessingOption::UNSHARPENING);
    }

    public function withWatermarkConfig(
        float $opacity,
        string $position = WatermarkPosition::CENTER,
        int $xOffset = 0,
        int $yOffset = 0,
        float $scale = 0.0
    ): self {
        switch ($position) {
            case WatermarkPosition::CENTER:
            case WatermarkPosition::NORTH:
            case WatermarkPosition::SOUTH:
            case WatermarkPosition::EAST:
            case WatermarkPosition::WEST:
            case WatermarkPosition::NORTH_EAST:
            case WatermarkPosition::NORTH_WEST:
            case WatermarkPosition::SOUTH_EAST:
            case WatermarkPosition::SOUTH_WEST:
            case WatermarkPosition::REPLICATE:
                break;
            default:
                throw new \InvalidArgumentException("unknown watermark position $position");
        }
        return $this->set(ProcessingOption::WATERMARK, $opacity, $position, $xOffset, $yOffset, $scale);
    }

    public function withoutWatermarkConfig(): self
    {
        return $this->unset(ProcessingOption::WATERMARK);
    }

    public function watermarkConfig(): ?array
    {
        return $this->get(ProcessingOption::WATERMARK);
    }

    public function withWatermarkUrl(string $url): self
    {
        return $this->withWatermarkEncodedUrl(base64_encode($url));
    }

    public function withWatermarkEncodedUrl(string $encodedUrl): self
    {
        return $this->set(ProcessingOption::WATERMARK, $encodedUrl);
    }

    public function withoutWatermarkUrl(): self
    {
        return $this->unset(ProcessingOption::WATERMARK);
    }

    public function watermarkUrl(): ?string
    {
        $encoded = $this->firstValue(ProcessingOption::WATERMARK_URL, 'string');
        return $encoded ? base64_decode($encoded) : null;
    }

    public function withSvgCssStyle(string $css): self
    {
        return $this->withSvgEncodedCssStyle(base64_encode($css));
    }

    public function withSvgEncodedCssStyle(string $encodedCss): self
    {
        return $this->set(ProcessingOption::STYLE, $encodedCss);
    }

    public function withoutSvgCssStyle(): self
    {
        return $this->unset(ProcessingOption::STYLE);
    }

    public function svgCssStyle(): ?string
    {
        $encoded = $this->firstValue(ProcessingOption::STYLE, 'string');
        return $encoded ? base64_decode($encoded) : null;
    }

    public function withJpegOptions(
        bool $progressive = false,
        bool $noSubsample = false,
        bool $trellisQuant = false,
        bool $overshootDeringing = false,
        bool $optimizeScans = false,
        int $quantTable = 0
    ): self {
        if (($quantTable < 0) || ($quantTable > 8)) {
            throw new \InvalidArgumentException("JPEG_QUANT_TABLE must be int 0-8");
        }
        return $this->set(
            ProcessingOption::JPEG_OPTIONS,
            (int)$progressive,
            (int)$noSubsample,
            (int)$trellisQuant,
            (int)$overshootDeringing,
            (int)$optimizeScans,
            $quantTable
        );
    }

    public function withoutJpegOptions(): self
    {
        return $this->unset(ProcessingOption::JPEG_OPTIONS);
    }

    public function jpegOptions(): ?array
    {
        return $this->get(ProcessingOption::JPEG_OPTIONS);
    }

    public function withPngOptions(
        bool $interlaced = false,
        bool $quantize = false,
        int $quantizationColors = 256
    ): self {
        if (($quantizationColors < 2) || ($quantizationColors > 256)) {
            throw new \InvalidArgumentException("PNG_QUANTIZATION_COLORS must be int 2-256");
        }
        return $this->set(
            ProcessingOption::PNG_OPTIONS,
            (int)$interlaced,
            (int)$quantize,
            $quantizationColors
        );
    }

    public function withoutPngOptions(): self
    {
        return $this->unset(ProcessingOption::PNG_OPTIONS);
    }

    public function PngOptions(): ?array
    {
        return $this->get(ProcessingOption::PNG_OPTIONS);
    }

    public function withGifOptions(
        bool $optimizeFrames = false,
        bool $optimizeTransparency = false
    ): self {
        return $this->set(
            ProcessingOption::GIF_OPTIONS,
            (int)$optimizeFrames,
            (int)$optimizeTransparency
        );
    }

    public function withoutGifOptions(): self
    {
        return $this->unset(ProcessingOption::GIF_OPTIONS);
    }

    public function GifOptions(): ?array
    {
        return $this->get(ProcessingOption::GIF_OPTIONS);
    }

    public function withPage(int $n): self
    {
        if ($n <= 0) {
            throw new \InvalidArgumentException("page must be >= 0");
        }
        return $this->set(ProcessingOption::PAGE, $n);
    }

    public function withoutPage(): self
    {
        return $this->unset(ProcessingOption::PAGE);
    }

    public function page(): ?int
    {
        return $this->firstValue(ProcessingOption::PAGE, 'int');
    }

    public function withVideoThumbnailSecond(int $n): self
    {
        if ($n <= 0) {
            throw new \InvalidArgumentException("video thumbnail second must be >= 0");
        }
        return $this->set(ProcessingOption::VIDEO_THUMBNAIL_SECOND, $n);
    }

    public function withoutVideoThumbnailSecond(): self
    {
        return $this->unset(ProcessingOption::VIDEO_THUMBNAIL_SECOND);
    }

    public function videoThumbnailSecond(): ?int
    {
        return $this->firstValue(ProcessingOption::VIDEO_THUMBNAIL_SECOND, 'int');
    }

    public function withPresets(string $preset1, string ...$morePresets): self
    {
        return $this->set(ProcessingOption::PRESET, $preset1, ...$morePresets);
    }

    public function withoutPresets(): self
    {
        return $this->unset(ProcessingOption::PRESET);
    }

    public function presets(): array
    {
        return (array)$this->get(ProcessingOption::PRESET);
    }

    public function withCacheBuster(string $id): self
    {
        return $this->set(ProcessingOption::CACHEBUSTER, $id);
    }

    public function withoutCacheBuster(): self
    {
        return $this->unset(ProcessingOption::CACHEBUSTER);
    }

    public function cacheBuster(): ?string
    {
        return $this->firstValue(ProcessingOption::CACHEBUSTER, 'string');
    }

    public function withStripMetadata(): self
    {
        return $this->set(ProcessingOption::STRIP_METADATA, 1);
    }

    public function withoutStripMetadata(): self
    {
        return $this->unset(ProcessingOption::STRIP_METADATA);
    }

    public function mustStripMetadata(): bool
    {
        return filter_var($this->firstValue(ProcessingOption::STRIP_METADATA, 'bool'), FILTER_VALIDATE_BOOL);
    }

    public function withStripColorProfile(): self
    {
        return $this->set(ProcessingOption::STRIP_COLOR_PROFILE, 1);
    }

    public function withoutStripColorProfile(): self
    {
        return $this->unset(ProcessingOption::STRIP_COLOR_PROFILE);
    }

    public function mustStripColorProfile(): bool
    {
        return filter_var($this->firstValue(ProcessingOption::STRIP_COLOR_PROFILE, 'bool'), FILTER_VALIDATE_BOOL);
    }

    public function withAutoRotate(): self
    {
        return $this->set(ProcessingOption::AUTO_ROTATE, 1);
    }

    public function withoutAutoRotate(): self
    {
        return $this->unset(ProcessingOption::AUTO_ROTATE);
    }

    public function mustAutoRotate(): bool
    {
        return filter_var($this->firstValue(ProcessingOption::AUTO_ROTATE, 'bool'), FILTER_VALIDATE_BOOL);
    }

    public function withFilename(string $filename): self
    {
        return $this->set(ProcessingOption::FILENAME, $filename);
    }

    public function withoutFilename(): self
    {
        return $this->unset(ProcessingOption::FILENAME);
    }

    public function filename(): string
    {
        return $this->firstValue(ProcessingOption::FILENAME, 'string');
    }

    public function withFormat(string $format): self
    {
        return $this->set(ProcessingOption::FORMAT, $format);
    }

    public function withoutFormat(): self
    {
        return $this->unset(ProcessingOption::FORMAT);
    }

    public function format(): string
    {
        return $this->firstValue(ProcessingOption::FORMAT, 'string');
    }

    protected function firstValue(string $name, string $type)
    {
        $o = $this->get($name);
        if ((null === $o) || (count($o) === 0)) {
            return null;
        }

        $val = $o[0];
        settype($val, $type);
        return $val;
    }
}