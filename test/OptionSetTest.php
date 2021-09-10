<?php

namespace Imgproxy\Test;

use Imgproxy\Gravity;
use Imgproxy\OptionSet;
use Imgproxy\ResizingAlgorithm;
use Imgproxy\ResizingType;
use Imgproxy\Rotate;
use Imgproxy\UnsharpeningMode;
use PHPUnit\Framework\TestCase;

class OptionSetTest extends TestCase
{
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
            [ResizingType::FIT, false],
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
        $this->assertEquals([32, "", false, false], $os->trim());

        $os->withTrim(32, "000000");
        $this->assertEquals([32, "000000", false, false], $os->trim());

        $os->withTrim(32, "000000", true);
        $this->assertEquals([32, "000000", true, false], $os->trim());

        $os->withTrim(32, "000000", true, true);
        $this->assertEquals([32, "000000", true, true], $os->trim());
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
}
