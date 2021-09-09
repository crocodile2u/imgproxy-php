<?php

namespace Imgproxy\Test;

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

    protected function assertPropertyUnset($unsetter, $getter, $setter, ...$value)
    {
        $os = new OptionSet();

        $os->$setter(...$value);
        $this->assertNotNull($os->$getter());

        $os->$unsetter();
        $this->assertNull($os->$getter());
    }
}
