<?php

namespace ProcessingOption;

use Imgproxy\ProcessingOption;
use Imgproxy\ProcessingOption\OptionSet;
use PHPUnit\Framework\TestCase;

class OptionSetTest extends TestCase
{

    public function testToString()
    {
        $o1 = $this->getMockBuilder(ProcessingOption::class)->getMock();
        $o1->method("toString")->willReturn("o1");
        $o1->method("name")->willReturn("o1");
        $o2 = $this->getMockBuilder(ProcessingOption::class)->getMockForAbstractClass();
        $o2->method("toString")->willReturn("o2");
        $o2->method("name")->willReturn("o2");

        $os = new OptionSet();
        $os->set($o1)->set($o2);

        $this->assertEquals("o1/o2", $os->toString());
    }
}
