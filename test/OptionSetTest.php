<?php

namespace Imgproxy\Test;

use Imgproxy\Gravity;
use Imgproxy\OptionSet;
use Imgproxy\ResizingAlgorithm;
use Imgproxy\ResizingType;
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

    public function testWithoutWidth()
    {
        $this->assertPropertyUnset('withoutWidth', 'width', 'withWidth', 1);
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

    public function testWithoutHeight()
    {
        $this->assertPropertyUnset('withoutHeight', 'height', 'withHeight', 1);
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

    public function testWithoutResizingType()
    {
        $this->assertPropertyUnset('withoutResizingType', 'resizingType', 'withResizingType', ResizingType::AUTO);
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

    public function testWithoutResizingAlgorithm()
    {
        $this->assertPropertyUnset('withoutResizingAlgorithm', 'resizingAlgorithm', 'withResizingAlgorithm', ResizingAlgorithm::LINEAR);
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

    public function testWithoutDpr()
    {
        $this->assertPropertyUnset('withoutDpr', 'dpr', 'withDpr', 2);
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

    public function testWithoutEnlarge()
    {
        $this->assertPropertyUnset('withoutEnlarge', 'enlarge', 'withEnlarge');
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

    public function testWithoutExtend()
    {
        $this->assertPropertyUnset('withoutExtend', 'extend', 'withExtend');
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

    public function testWithoutGravity()
    {
        $this->assertPropertyUnset('withoutGravity', 'gravity', 'withGravity', Gravity::CENTER);
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

    public function testWithoutCrop()
    {
        $this->assertPropertyUnset('withoutCrop', 'crop', 'withCrop', 10, 10);
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

    public function testWithoutPadding()
    {
        $this->assertPropertyUnset('withoutPadding', 'padding', 'withPadding', 10, 10, 10, 10);
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

    public function testWithoutTrim()
    {
        $this->assertPropertyUnset('withoutTrim', 'trim', 'withTrim', 32);
    }

    protected function assertPropertyUnset($unsetter, $getter, $setter, ...$value)
    {
        $os = new OptionSet();

        $os->$setter(...$value);
        $this->assertNotNull($os->$getter());

        $os->$unsetter();
        $this->assertNull($os->$getter());
    }
}
