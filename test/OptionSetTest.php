<?php

namespace Imgproxy\Test;

use Imgproxy\OptionSet;
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
        $os = new OptionSet();

        $os->withWidth(1);
        $this->assertNotNull($os->width());

        $os->withoutWidth();
        $this->assertNull($os->width());
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
        $os = new OptionSet();

        $os->withHeight(1);
        $this->assertNotNull($os->height());

        $os->withoutHeight();
        $this->assertNull($os->height());
    }

    public function providerSize()
    {
        return [
            "valid 0" => [0, false],
            "valid > 0" => [1, false],
            "invalid" => [-1, true],
        ];
    }
}
