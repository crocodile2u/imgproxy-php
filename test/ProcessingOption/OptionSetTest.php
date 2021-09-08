<?php

namespace ProcessingOption;

use Imgproxy\ProcessingOption;
use Imgproxy\ProcessingOption\OptionSet;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class OptionSetTest extends TestCase
{

    public function testToString()
    {
        $os = new OptionSet();
        $os
            ->set($this->createMockOption("o1"))
            ->set($this->createMockOption("o2"));

        $this->assertEquals("o1/o2", $os->toString());
    }

    /**
     * @return MockObject|ProcessingOption
     */
    private function createMockOption(string $name) {
        $opt = $this->getMockBuilder(ProcessingOption::class)->getMock();
        $opt->method("toString")->willReturn($name);
        $opt->method("name")->willReturn($name);
        return $opt;
    }
}
