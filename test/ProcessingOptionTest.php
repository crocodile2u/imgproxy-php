<?php


use Imgproxy\ProcessingOption;
use PHPUnit\Framework\TestCase;

class ProcessingOptionTest extends TestCase
{

    public function testName()
    {
        $this->assertEquals("test", $this->createStubProcessingOption()->name());
    }

    public function testValues()
    {
        $values = ["a1", "a2"];
        $this->assertEquals($values, $this->createStubProcessingOption(...$values)->values());
    }

    public function testFirstValue()
    {
        $values = ["a1", "a2"];
        $this->assertEquals("a1", $this->createStubProcessingOption(...$values)->firstValue());
    }

    /**
     * @param array $values
     * @param $expected
     * @dataProvider providerForToString
     */
    public function testToString(array $values, $expected)
    {
        $this->assertEquals($expected, $this->createStubProcessingOption(...$values)->toString());
    }

    private function createStubProcessingOption(...$values): ProcessingOption {
        return new class(...$values) extends ProcessingOption {
            protected $name = "test";
        };
    }

    public function providerForToString()
    {
        return [
            "no arguments" => [[], "test"],
            "one argument" => [["a1"], "test:a1"],
            "multiple arguments" => [["a1", "a2", "a3"], "test:a1:a2:a3"],
        ];
    }
}
