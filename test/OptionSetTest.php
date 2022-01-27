<?php

namespace Imgproxy\Test;

use Imgproxy\Gravity;
use Imgproxy\OptionSet;
use Imgproxy\ProcessingOption;
use Imgproxy\ResizingAlgorithm;
use Imgproxy\ResizingType;
use Imgproxy\Rotate;
use Imgproxy\UnsharpeningMode;
use Imgproxy\WatermarkPosition;
use PHPUnit\Framework\TestCase;

class OptionSetTest extends TestCase
{
    /**
     * @param OptionSet $os
     * @param string $expected
     * @dataProvider provideToString
     */
    public function testToString(OptionSet $os, string $expected)
    {
        $this->assertEquals($expected, $os->toString());
    }

    public function provideToString()
    {
        return [
            [
                (new OptionSet()),
                ""
            ],
            [
                (new OptionSet())->withWidth(100)->withHeight(200),
                "w:100/h:200"
            ],
            [
                (new OptionSet())
                    ->withWidth(100)
                    ->withHeight(200)
                    ->withResizingType(ResizingType::FIT)
                    ->withResizingAlgorithm(ResizingAlgorithm::LINEAR)
                    ->withDpr(2)
                    ->withEnlarge()
                    ->withExtend()
                    ->withGravity(Gravity::SOUTH)
                    ->withCrop(100, 200)
                    ->withPadding(1, 2, 3, 4)
                    ->withTrim(32)
                    ->withRotate(Rotate::CLOCKWISE)
                    ->withMaxBytes(100)
                    ->withBackgroundRGB(50, 100, 150)
                    ->withBackgroundAlpha(.5)
                    ->withBrightness(100)
                    ->withContrast(.5)
                    ->withSaturation(.5)
                    ->withBlur(2.5)
                    ->withSharpen(2.5)
                    ->withPixelate(5)
                    ->withUnsharpening(UnsharpeningMode::ALWAYS, 2, 30)
                    ->withWatermarkConfig(0.5)
                    ->withWatermarkUrl("http://example.com")
                    ->withSvgCssStyle(".class{}")
                    ->withJpegOptions()
                    ->withPngOptions()
                    ->withGifOptions()
                    ->withPage(5)
                    ->withVideoThumbnailSecond(500)
                    ->withPresets("p1", "p2")
                    ->withCacheBuster("cb-id")
                    ->withStripMetadata()
                    ->withStripColorProfile()
                    ->withAutoRotate()
                    ->withFilename("test.png")
                    ->withFormat("png"),
                "w:100/h:200/rt:fit/ra:linear/dpr:2/el:1/ex:1/g:so:0:0/c:100:200/pd:1:2:3:4/t:32/rot:90/mb:100/bg:50:100:150/bga:0.5/br:100/co:0.5/sa:0.5/bl:2.5/sh:2.5/pix:5/ush:always:2:30/wm:0.5:ce:0:0:0/wmu:" .
                base64_encode("http://example.com") .
                "/st:" . base64_encode(".class{}") .
                "/jpgo:0:0:0:0:0:0/pngo:0:0:256/gifo:0:0/pg:5/vts:500/pr:p1:p2/cb:cb-id/sm:1/scp:1/ar:1/fn:test.png/f:png"
            ],
        ];
    }

    /**
     * @param $w
     * @param $expectException
     * @dataProvider providerSize
     */
    public function testWithWidth($w, $expectException)
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withWidth($w);
        $this->assertEquals($w, $os->width());
    }

    /**
     * @param $w
     * @param $expectException
     * @dataProvider providerSize
     */
    public function testWithHeight($w, $expectException)
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withHeight($w);
        $this->assertEquals($w, $os->height());
    }

    public function providerSize()
    {
        return [
            "valid 0" => [0, false],
            "valid > 0" => [1, false],
            "invalid" => [-1, true],
        ];
    }

    /**
     * @param $rt
     * @param $expectException
     * @dataProvider providerResizingType
     */
    public function testWithResizingType($rt, $expectException)
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withResizingType($rt);
        $this->assertEquals($rt, $os->resizingType());
    }

    public function providerResizingType()
    {
        return [
            [ResizingType::AUTO, false],
            [ResizingType::FILL, false],
            [ResizingType::FILL_DOWN, false],
            [ResizingType::FIT, false],
            [ResizingType::FORCE, false],
            ["invalid", true],
        ];
    }

    /**
     * @param $ra
     * @param $expectException
     * @dataProvider providerResizingAlgorithm
     */
    public function testWithResizingAlgorithm($ra, $expectException)
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withResizingAlgorithm($ra);
        $this->assertEquals($ra, $os->resizingAlgorithm());
    }

    public function providerResizingAlgorithm()
    {
        return [
            [ResizingAlgorithm::LANCZOS3, false],
            [ResizingAlgorithm::LANCZOS2, false],
            [ResizingAlgorithm::CUBIC, false],
            [ResizingAlgorithm::LINEAR, false],
            [ResizingAlgorithm::NEAREST, false],
            ["invalid", true],
        ];
    }

    /**
     * @param $val
     * @param $expectException
     * @dataProvider providerDpr
     */
    public function testWithDpr($val, $expectException)
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withDpr($val);
        $this->assertEquals($val, $os->dpr());
    }

    public function providerDpr()
    {
        return [
            [1, false],
            [2, false],
            [10, false],
            [0, true],
            [-1, true],
        ];
    }

    public function testWithEnlarge()
    {
        $os = new OptionSet();
        $os->withEnlarge();
        $this->assertEquals(1, $os->enlarge());
    }

    /**
     * @param $expectException
     * @param $expectedArgs
     * @param mixed ...$gravityOptions
     * @dataProvider providerExtend
     */
    public function testWithExtend($expectException, $expectedArgs, ...$gravityOptions)
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withExtend(...$gravityOptions);
        $this->assertEquals($expectedArgs, $os->extend());
    }

    public function providerExtend()
    {
        return [
            [false, [1]],
            [false, [1, Gravity::NORTH, 0, 0], Gravity::NORTH],
            [false, [1, Gravity::NORTH, 10, 20], Gravity::NORTH, 10, 20],
            [false, [1, Gravity::SOUTH, 0, 0], Gravity::SOUTH],
            [false, [1, Gravity::SOUTH, 10, 20], Gravity::SOUTH, 10, 20],
            [false, [1, Gravity::EAST, 0, 0], Gravity::EAST],
            [false, [1, Gravity::EAST, 10, 20], Gravity::EAST, 10, 20],
            [false, [1, Gravity::WEST, 0, 0], Gravity::WEST],
            [false, [1, Gravity::WEST, 10, 20], Gravity::WEST, 10, 20],
            [false, [1, Gravity::NORTH_EAST, 0, 0], Gravity::NORTH_EAST],
            [false, [1, Gravity::NORTH_EAST, 10, 20], Gravity::NORTH_EAST, 10, 20],
            [false, [1, Gravity::NORTH_WEST, 0, 0], Gravity::NORTH_WEST],
            [false, [1, Gravity::NORTH_WEST, 10, 20], Gravity::NORTH_WEST, 10, 20],
            [false, [1, Gravity::SOUTH_EAST, 0, 0], Gravity::SOUTH_EAST],
            [false, [1, Gravity::SOUTH_EAST, 10, 20], Gravity::SOUTH_EAST, 10, 20],
            [false, [1, Gravity::SOUTH_WEST, 0, 0], Gravity::SOUTH_WEST],
            [false, [1, Gravity::SOUTH_WEST, 10, 20], Gravity::SOUTH_WEST, 10, 20],
            [false, [1, Gravity::CENTER, 0, 0], Gravity::CENTER],
            [false, [1, Gravity::CENTER, 10, 20], Gravity::CENTER, 10, 20],
            [false, [1, Gravity::FOCUS_POINT, 0, 0], Gravity::FOCUS_POINT],
            [false, [1, Gravity::FOCUS_POINT, 1, 1], Gravity::FOCUS_POINT, 1, 1],
            [false, [1, Gravity::FOCUS_POINT, 0.5, 0.75], Gravity::FOCUS_POINT, 0.5, 0.75],
            [true, [], Gravity::FOCUS_POINT, -0.1, 0],
            [true, [], Gravity::FOCUS_POINT, 0, -0.1],
        ];
    }

    /**
     * @param $expectException
     * @param $expectedArgs
     * @param $type
     * @param $x
     * @param $y
     * @dataProvider providerGravity
     */
    public function testWithGravity($expectException, $expectedArgs, $type, $x = null, $y = null)
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withGravity($type, $x, $y);
        $this->assertEquals($expectedArgs, $os->gravity());
    }

    public function providerGravity()
    {
        return [
            [false, [Gravity::NORTH, 0, 0], Gravity::NORTH],
            [false, [Gravity::NORTH, 10, 20], Gravity::NORTH, 10, 20],
            [false, [Gravity::SOUTH, 0, 0], Gravity::SOUTH],
            [false, [Gravity::SOUTH, 10, 20], Gravity::SOUTH, 10, 20],
            [false, [Gravity::EAST, 0, 0], Gravity::EAST],
            [false, [Gravity::EAST, 10, 20], Gravity::EAST, 10, 20],
            [false, [Gravity::WEST, 0, 0], Gravity::WEST],
            [false, [Gravity::WEST, 10, 20], Gravity::WEST, 10, 20],
            [false, [Gravity::NORTH_EAST, 0, 0], Gravity::NORTH_EAST],
            [false, [Gravity::NORTH_EAST, 10, 20], Gravity::NORTH_EAST, 10, 20],
            [false, [Gravity::NORTH_WEST, 0, 0], Gravity::NORTH_WEST],
            [false, [Gravity::NORTH_WEST, 10, 20], Gravity::NORTH_WEST, 10, 20],
            [false, [Gravity::SOUTH_EAST, 0, 0], Gravity::SOUTH_EAST],
            [false, [Gravity::SOUTH_EAST, 10, 20], Gravity::SOUTH_EAST, 10, 20],
            [false, [Gravity::SOUTH_WEST, 0, 0], Gravity::SOUTH_WEST],
            [false, [Gravity::SOUTH_WEST, 10, 20], Gravity::SOUTH_WEST, 10, 20],
            [false, [Gravity::CENTER, 0, 0], Gravity::CENTER],
            [false, [Gravity::CENTER, 10, 20], Gravity::CENTER, 10, 20],
            [false, [Gravity::FOCUS_POINT, 0, 0], Gravity::FOCUS_POINT],
            [false, [Gravity::FOCUS_POINT, 1, 1], Gravity::FOCUS_POINT, 1, 1],
            [false, [Gravity::FOCUS_POINT, 0.5, 0.75], Gravity::FOCUS_POINT, 0.5, 0.75],
            [true, [], Gravity::FOCUS_POINT, -0.1, 0],
            [true, [], Gravity::FOCUS_POINT, 0, -0.1],
            [true, [], "invalid", 0, 0],
        ];
    }

    /**
     * @param $expectException
     * @param $expectedArgs
     * @param $w
     * @param $h
     * @param mixed ...$gravityOptions
     * @dataProvider providerCrop
     */
    public function testWithCrop($expectException, $expectedArgs, $w, $h, ...$gravityOptions)
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withCrop($w, $h, ...$gravityOptions);
        $this->assertEquals($expectedArgs, $os->crop());
    }

    public function providerCrop()
    {
        return [
            [false, [10, 10], 10, 10],
            [false, [10, 10, Gravity::NORTH, 0, 0], 10, 10, Gravity::NORTH],
            [false, [10, 10, Gravity::NORTH, 10, 20], 10, 10, Gravity::NORTH, 10, 20],
            [false, [10, 10, Gravity::SOUTH, 0, 0], 10, 10, Gravity::SOUTH],
            [false, [10, 10, Gravity::SOUTH, 10, 20], 10, 10, Gravity::SOUTH, 10, 20],
            [false, [10, 10, Gravity::EAST, 0, 0], 10, 10, Gravity::EAST],
            [false, [10, 10, Gravity::EAST, 10, 20], 10, 10, Gravity::EAST, 10, 20],
            [false, [10, 10, Gravity::WEST, 0, 0], 10, 10, Gravity::WEST],
            [false, [10, 10, Gravity::WEST, 10, 20], 10, 10, Gravity::WEST, 10, 20],
            [false, [10, 10, Gravity::NORTH_EAST, 0, 0], 10, 10, Gravity::NORTH_EAST],
            [false, [10, 10, Gravity::NORTH_EAST, 10, 20], 10, 10, Gravity::NORTH_EAST, 10, 20],
            [false, [10, 10, Gravity::NORTH_WEST, 0, 0], 10, 10, Gravity::NORTH_WEST],
            [false, [10, 10, Gravity::NORTH_WEST, 10, 20], 10, 10, Gravity::NORTH_WEST, 10, 20],
            [false, [10, 10, Gravity::SOUTH_EAST, 0, 0], 10, 10, Gravity::SOUTH_EAST],
            [false, [10, 10, Gravity::SOUTH_EAST, 10, 20], 10, 10, Gravity::SOUTH_EAST, 10, 20],
            [false, [10, 10, Gravity::SOUTH_WEST, 0, 0], 10, 10, Gravity::SOUTH_WEST],
            [false, [10, 10, Gravity::SOUTH_WEST, 10, 20], 10, 10, Gravity::SOUTH_WEST, 10, 20],
            [false, [10, 10, Gravity::CENTER, 0, 0], 10, 10, Gravity::CENTER],
            [false, [10, 10, Gravity::CENTER, 10, 20], 10, 10, Gravity::CENTER, 10, 20],
            [false, [10, 10, Gravity::FOCUS_POINT, 0, 0], 10, 10, Gravity::FOCUS_POINT],
            [false, [10, 10, Gravity::FOCUS_POINT, 1, 1], 10, 10, Gravity::FOCUS_POINT, 1, 1],
            [false, [10, 10, Gravity::FOCUS_POINT, 0.5, 0.75], 10, 10, Gravity::FOCUS_POINT, 0.5, 0.75],
            [true, [], 10, 10, Gravity::FOCUS_POINT, -0.1, 0],
            [true, [], 10, 10, Gravity::FOCUS_POINT, 0, -0.1],
        ];
    }

    /**
     * @param $expectException
     * @param int $t
     * @param int $r
     * @param int $b
     * @param int $l
     * @dataProvider providerPadding
     */
    public function testWithPadding($expectException, int $t, int $r, int $b, int $l)
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withPadding($t, $r, $b, $l);
        $this->assertEquals([$t, $r, $b, $l], $os->padding());
    }

    public function providerPadding()
    {
        return [
            [false, 10, 10, 10, 10],
            [false, 0, 10, 10, 10],
            [false, 0, 0, 10, 10],
            [false, 0, 0, 0, 10],
            [true, 0, 0, 0, 0],
            [true, -1, 10, 10, 10],
            [true, 10, -1, 10, 10],
            [true, 10, 10, -1, 10],
            [true, 10, 10, 10, -1],
        ];
    }

    public function testWithTrim()
    {
        $os = new OptionSet();

        $os->withTrim(32);
        $this->assertEquals([32], $os->trim());

        $os->withTrim(32, "000000");
        $this->assertEquals([32, "000000"], $os->trim());

        $os->withTrim(32, "000000", true);
        $this->assertEquals([32, "000000", 1], $os->trim());

        $os->withTrim(32, "000000", true, true);
        $this->assertEquals([32, "000000", 1, 1], $os->trim());
    }

    public function testWithTrimTransparentBackground()
    {
        $os = new OptionSet();
        $os->withTrimTransparentBackground(32);
        $this->assertEquals([32, OptionSet::TRANSPARENT_BG, false, false], $os->trim());
    }

    /**
     * @param $expectException
     * @param $angle
     * @dataProvider providerRotate
     */
    public function testWithRotate($expectException, $angle)
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withRotate($angle);
        $this->assertEquals($angle, $os->rotate());
    }

    public function providerRotate()
    {
        return [
            [false, Rotate::NONE],
            [false, Rotate::CLOCKWISE],
            [false, Rotate::COUNTERCLOCKWISE],
            [false, Rotate::UPSIDE_DOWN],
            [true, 1],
        ];
    }

    /**
     * @param $expectException
     * @param $value
     * @dataProvider providerMaxBytes
     */
    public function testWithMaxBytes($expectException, $value)
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withMaxBytes($value);
        $this->assertEquals($value, $os->maxBytes());
    }

    public function providerMaxBytes()
    {
        return [
            [false, 1],
            [false, 1000000],
            [true, 0],
            [true, -1],
        ];
    }

    /**
     * @param $expectException
     * @param $r
     * @param $g
     * @param $b
     * @dataProvider providerBackgroundRGB
     */
    public function testWithBackgroundRGB($expectException, $r, $g, $b)
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withBackgroundRGB($r, $g, $b);
        $this->assertEquals([$r, $g, $b], $os->background());
    }

    public function providerBackgroundRGB()
    {
        return [
            [false, 0, 0, 0],
            [false, 100, 100, 100],
            [false, 255, 255, 255],
            [true, -1, 0, 0],
            [true, 0, -1, 0],
            [true, 0, 0, -1],
            [true, 256, 0, 0],
            [true, 0, 256, 0],
            [true, 0, 0, 256],
        ];
    }

    /**
     * @param $expectException
     * @param $bg
     * @dataProvider providerBackgroundHex
     */
    public function testWithBackgroundHex($expectException, $bg)
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withBackgroundHex($bg);
        $this->assertEquals([$bg], $os->background());
    }

    public function providerBackgroundHex()
    {
        return [
            [false, "000000"],
            [false, "FFFFFF"],
            [true, "too long"],
            [true, "short"],
        ];
    }

    /**
     * @param $expectException
     * @param $alpha
     * @dataProvider providerFloatFromZeroToOne
     */
    public function testWithBackgroundAlpha($expectException, $alpha)
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withBackgroundAlpha($alpha);
        $this->assertEquals($alpha, $os->backgroundAlpha());
    }

    /**
     * @param $expectException
     * @param $val
     * @dataProvider providerBrightness
     */
    public function testWithBrightness($expectException, $val)
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withBrightness($val);
        $this->assertEquals($val, $os->brightness());
    }

    public function providerBrightness()
    {
        return [
            [false, -255],
            [false, 0],
            [false, 255],
            [true, -256],
            [true, 256],
        ];
    }

    /**
     * @param $expectException
     * @param $val
     * @dataProvider providerFloatFromZeroToOne
     */
    public function testWithContrast($expectException, $val)
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withContrast($val);
        $this->assertEquals($val, $os->contrast());
    }

    /**
     * @param $expectException
     * @param $val
     * @dataProvider providerFloatFromZeroToOne
     */
    public function testWithSaturation($expectException, $val)
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withSaturation($val);
        $this->assertEquals($val, $os->saturation());
    }

    /**
     * @param $expectException
     * @param $val
     * @dataProvider providerPositiveFloat
     */
    public function testWithBlur($expectException, $val)
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withBlur($val);
        $this->assertEquals($val, $os->blur());
    }

    /**
     * @param $expectException
     * @param $val
     * @dataProvider providerPositiveFloat
     */
    public function testWithSharpen($expectException, $val)
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withSharpen($val);
        $this->assertEquals($val, $os->sharpen());
    }

    /**
     * @param $expectException
     * @param $val
     * @dataProvider providerPositiveInt
     */
    public function testWithPixelate($expectException, $val)
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withPixelate($val);
        $this->assertEquals($val, $os->pixelate());
    }

    /**
     * @param $expectException
     * @param string $mode
     * @param float $weight
     * @param float $dividor
     * @dataProvider providerUnsharpening
     */
    public function testWithUnsharpening(
        $expectException,
        string $mode,
        ?float $weight,
        ?float $dividor,
        array $expectedArgs
    ) {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withUnsharpening($mode, $weight, $dividor);
        $this->assertEquals($expectedArgs, $os->unsharpening());
    }

    public function providerUnsharpening()
    {
        return [
            [
                false,
                UnsharpeningMode::NONE,
                null,
                null,
                [UnsharpeningMode::NONE, 1, OptionSet::DEFAULT_UNSHARPENING_DIVIDOR]
            ],
            [
                false,
                UnsharpeningMode::AUTO,
                2,
                30,
                [UnsharpeningMode::AUTO, 2, 30]
            ],
            [
                false,
                UnsharpeningMode::ALWAYS,
                4,
                60,
                [UnsharpeningMode::ALWAYS, 4, 60]
            ],
            [
                true,
                "invalid",
                null,
                null,
                []
            ],
            [
                true,
                UnsharpeningMode::ALWAYS,
                0,
                60,
                []
            ],
            [
                true,
                UnsharpeningMode::ALWAYS,
                1,
                0,
                []
            ],
        ];
    }

    /**
     * @param $expectException
     * @param $expectedArgs
     * @param float $opacity
     * @param mixed ...$positionOptions
     * @dataProvider providerWatermarkConfig
     */
    public function testWithWatermarkConfig($expectException, $expectedArgs, float $opacity, ...$positionOptions)
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withWatermarkConfig($opacity, ...$positionOptions);
        $this->assertEquals($expectedArgs, $os->watermarkConfig());
    }

    public function providerWatermarkConfig()
    {
        return [
            [false, [0.0, WatermarkPosition::CENTER, 0, 0, 0.0], 0.0],
            [false, [1.0, WatermarkPosition::CENTER, 0, 0, 0.0], 1.0],
            [false, [0.5, WatermarkPosition::CENTER, 0, 0, 0.0], .5],
            [true, [], -0.1],
            [true, [], 1.1],
            [false, [0.0, WatermarkPosition::NORTH, 0, 0, 0.0], 0.0, WatermarkPosition::NORTH],
            [false, [0.0, WatermarkPosition::SOUTH, 0, 0, 0.0], 0.0, WatermarkPosition::SOUTH],
            [false, [0.0, WatermarkPosition::EAST, 0, 0, 0.0], 0.0, WatermarkPosition::EAST],
            [false, [0.0, WatermarkPosition::WEST, 0, 0, 0.0], 0.0, WatermarkPosition::WEST],
            [false, [0.0, WatermarkPosition::NORTH_EAST, 0, 0, 0.0], 0.0, WatermarkPosition::NORTH_EAST],
            [false, [0.0, WatermarkPosition::NORTH_WEST, 0, 0, 0.0], 0.0, WatermarkPosition::NORTH_WEST],
            [false, [0.0, WatermarkPosition::SOUTH_EAST, 0, 0, 0.0], 0.0, WatermarkPosition::SOUTH_EAST],
            [false, [0.0, WatermarkPosition::SOUTH_WEST, 0, 0, 0.0], 0.0, WatermarkPosition::SOUTH_WEST],
            [false, [0.0, WatermarkPosition::REPLICATE, 0, 0, 0.0], 0.0, WatermarkPosition::REPLICATE],
            [false, [0.0, WatermarkPosition::SOUTH_WEST, 10, 10, 1.5], 0.0, WatermarkPosition::SOUTH_WEST, 10, 10, 1.5],
            [true, [], 0.0, "invalid"],
        ];
    }

    public function testWithWatermarkUrl()
    {
        $os = new OptionSet();
        $url = "http://example.com";
        $os->withWatermarkUrl($url);
        $this->assertEquals($url, $os->watermarkUrl());
    }

    public function testWithWatermarkEncodedUrl()
    {
        $os = new OptionSet();
        $url = "http://example.com";
        $os->withWatermarkEncodedUrl(base64_encode($url));
        $this->assertEquals($url, $os->watermarkUrl());
    }

    public function testWithSvgCssStyle()
    {
        $os = new OptionSet();
        $style = ".class{}";
        $os->withSvgCssStyle($style);
        $this->assertEquals($style, $os->svgCssStyle());
    }

    public function testWithSvgEncodedCssStyle()
    {
        $os = new OptionSet();
        $style = ".class{}";
        $os->withSvgEncodedCssStyle(base64_encode($style));
        $this->assertEquals($style, $os->svgCssStyle());
    }

    /**
     * @param $expectException
     * @param $expectedArgs
     * @param bool $progressive
     * @param bool $noSubsample
     * @param bool $trellisQuant
     * @param bool $overshootDeringing
     * @param bool $optimizeScans
     * @param int $quantTable
     * @dataProvider provideJpegOptions
     */
    public function testWithJpegOptions(
        $expectException,
        $expectedArgs,
        bool $progressive,
        bool $noSubsample,
        bool $trellisQuant,
        bool $overshootDeringing,
        bool $optimizeScans,
        int $quantTable
    ) {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withJpegOptions(
            $progressive,
            $noSubsample,
            $trellisQuant,
            $overshootDeringing,
            $optimizeScans,
            $quantTable
        );
        $this->assertEquals($expectedArgs, $os->jpegOptions());
    }

    public function provideJpegOptions()
    {
        return [
            [false, [0, 0, 0, 0, 0, 0], false, false, false, false, false, 0],
            [false, [1, 1, 1, 1, 1, 8], true, true, true, true, true, 8],
            [true, [], false, false, false, false, false, -1],
            [true, [], false, false, false, false, false, 9],
        ];
    }

    /**
     * @param $expectException
     * @param $expectedArgs
     * @param bool $interlaced
     * @param bool $quantize
     * @param int $quantizationColors
     * @dataProvider providePngOptions
     */
    public function testWithPngOptions(
        $expectException,
        $expectedArgs,
        bool $interlaced,
        bool $quantize,
        int $quantizationColors
    ) {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withPngOptions($interlaced, $quantize, $quantizationColors);
        $this->assertEquals($expectedArgs, $os->pngOptions());
    }

    public function providePngOptions()
    {
        return [
            [false, [0, 0, 2], false, false, 2],
            [false, [1, 1, 256], true, true, 256],
            [true, [], false, false, 1],
            [true, [], false, false, 0],
            [true, [], false, false, -1],
            [true, [], false, false, 257],
        ];
    }

    /**
     * @param bool $optimizeFrames
     * @param bool $optimizeTransparency
     * @dataProvider provideGifOptions
     */
    public function testWithGifOptions(bool $optimizeFrames, bool $optimizeTransparency) {
        $os = new OptionSet();
        $os->withGifOptions($optimizeFrames, $optimizeTransparency);
        $this->assertEquals([$optimizeFrames ? 1 : 0, $optimizeTransparency ? 1 : 0], $os->gifOptions());
    }

    public function provideGifOptions()
    {
        return [
            [false, false],
            [true, true],
            [false, true],
            [true, false],
        ];
    }

    /**
     * @param $expectException
     * @param int $n
     * @dataProvider providerPositiveInt
     */
    public function testWithPage($expectException, int $n)
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withPage($n);
        $this->assertEquals($n, $os->page());
    }

    /**
     * @param $expectException
     * @param int $n
     * @dataProvider providerPositiveInt
     */
    public function testWithVideoThumbnailSecond($expectException, int $n)
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $os = new OptionSet();
        $os->withVideoThumbnailSecond($n);
        $this->assertEquals($n, $os->videoThumbnailSecond());
    }

    /**
     * @param mixed ...$presets
     * @dataProvider providerPresets
     */
    public function testWithPresets(...$presets)
    {
        $os = new OptionSet();
        $os->withPresets(...$presets);
        $this->assertEquals($presets, $os->presets());
    }

    public function providerPresets()
    {
        return [
            ["p1"],
            ["p1", "p2"],
            ["p1", "p2", "pN"],
        ];
    }

    public function testWithCacheBuster()
    {
        $os = new OptionSet();
        $os->withCacheBuster("cb");
        $this->assertEquals("cb", $os->cacheBuster());
    }

    public function testWithStripMetadata()
    {
        $os = new OptionSet();
        $os->withStripMetadata();
        $this->assertTrue($os->mustStripMetadata());
    }

    public function testWithStripColorProfile()
    {
        $os = new OptionSet();
        $os->withStripColorProfile();
        $this->assertTrue($os->mustStripColorProfile());
    }

    public function testWithAutoRotate()
    {
        $os = new OptionSet();
        $os->withAutoRotate();
        $this->assertTrue($os->mustAutoRotate());
    }

    public function testWithFilename()
    {
        $os = new OptionSet();
        $os->withFilename("filename");
        $this->assertEquals("filename", $os->filename());
    }

    public function testWithFormat()
    {
        $os = new OptionSet();
        $os->withFormat("webp");
        $this->assertEquals("webp", $os->format());
    }

    public function testUnset()
    {
        $os = new OptionSet();
        $os->withPresets("test");
        $os->unset(ProcessingOption::PRESET);
        $this->assertNull($os->presets());
    }

    public function providerPositiveFloat()
    {
        return [
            [false, 0.1],
            [false, 1],
            [false, 10.1],
            [true, 0],
            [true, -0.1],
        ];
    }

    public function providerPositiveInt()
    {
        return [
            [false, 1],
            [false, 10],
            [true, 0],
            [true, -1],
        ];
    }

    public function providerFloatFromZeroToOne()
    {
        return [
            [false, 0],
            [false, 1],
            [false, 0.5],
            [true, -0.1],
            [true, 1.1],
        ];
    }
}
