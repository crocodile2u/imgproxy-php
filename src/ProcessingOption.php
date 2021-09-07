<?php

declare(strict_types=1);

namespace Imgproxy;

abstract class ProcessingOption
{
    public const WIDTH = 'w',
        HEIGHT = 'h',
        RESIZING_TYPE = 'rt',
        RESIZING_ALGORITHM = 'ra',
        DPR = 'dpr',
        ENLARGE = 'el',
        EXTEND = 'ex',
        GRAVITY = 'g',
        CROP = 'c',
        PADDING = 'pd',
        TRIM = 't',
        ROTATE = 'rot',
        QUALITY = 'q',
        MAX_BYTES = 'mb',
        BACKGROUND = 'bg',
        BACKGROUND_ALPHA = 'bga',
        ADJUST = 'a',
        BRIGHTNESS = 'br',
        CONTRAST = 'co',
        SATURATION = 'sa',
        BLUR = 'bl',
        SHARPEN = 'sh',
        PIXELATE = 'pix',
        UNSHARPENING = 'ush',
        WATERMARK = 'wm',
        WATERMARK_URL = 'wmu',
        STYLE = 'st',
        JPEG_OPTIONS = 'jpgo',
        PNG_OPTIONS = 'pngo',
        GIF_OPTIONS = 'gifo',
        PAGE = 'pg',
        VIDEO_THUMBNAIL_SECOND = 'vts',
        PRESET = 'pr',
        CACHEBUSTER = 'cb',
        STRIP_METADATA = 'sm',
        STRIP_COLOR_PROFILE = 'scp',
        AUTO_ROTATE = 'ar',
        FILENAME = 'fn',
        FORMAT = 'f';

    public abstract function name(): string;
    public abstract function toString(): string;

    protected function format(string $name, ...$args): string {
        $nameAndArgs = array_merge([$name], $args);
        return join(":", $nameAndArgs);// not using [$name, ...$args] to keep it 7.2 compatible
    }
}